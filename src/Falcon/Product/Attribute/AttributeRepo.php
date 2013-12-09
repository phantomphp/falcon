<?php

namespace Falcon\Product\Attribute;

use Falcon\Repository\RepositoryAwareInterface;
use Falcon\Repository\RepositoryInterface;

class AttributeRepo implements RepositoryAwareInterface
{
    protected $repository;
    
    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    public function assembleAttribute($data)
    {
        $attr = new Attribute($data['id'], $data['label'], $data['type']);
        if (isset($data['children']) && !empty($data['children'])) {
            foreach ($data['children'] as $child) {
                $attr->addChild($this->assembleAttribute($child));
            }
        }
        if (isset($data['value']) && $data['value'] !== '') {
            $attr->setValue($data['value']);
        }
        return $attr;
    }
    
    public function findAll()
    {
        return $this->fetchAll();
    }
    
    public function fetchAll()
    {
        $data = array();
        $attributes =  new AttributeCollection;
        $result = $this->repository->select('attribute', array('*'));
        foreach ($result as $record) {
            $parentId = $record['parent_id'];
            unset($record['parent_id']);
            if (!empty($parentId)) {
                $data[$parentId]['children'][] = $record;
            } else {
                $data[$record['id']] = $record;
            }
        }
        
        foreach ($data as $attr) {
            $attributes->add($this->assembleAttribute($attr));
        }

        return $attributes;
    }

    
    public function saveAttribute(Attribute $attr, $parentId = null)
    {
        $data = array(
            'id' => $attr->getId(),
            'label' => $attr->getLabel(),
            'type' => $attr->getType()
        );
        if ($parentId) {
            $data['parent_id'] = $parentId;
        }
        
        if ($attr->getId()) {
            $this->repository->update('attribute', $data, array('id' => $attr->getId()));
            $parentId = $attr->getId();
        } else {
            $parentId = $this->repository->insert('attribute', $data);
        }
        
        if ($attr->hasChildren()) {
            foreach ($attr->getChildren() as $child) {
                $this->saveAttribute($child, $parentId);
            }
        }
        
        return true;
    }
    
    public function save($data)
    {
        $attr = new Attribute($data['id'], $data['label'], $data['type']);
        if(in_array($data['type'], array(Attribute::TYPE_SET_RADIO, Attribute::TYPE_SET_CHECKBOX, Attribute::TYPE_SELECT))) {
            if (isset($data['new-options'])) {
                foreach ($data['new-options'] as $option) {
                    $child = new Attribute(0, $option, Attribute::TYPE_OPTION);
                    $attr->addChild($child);
                }
            }
            if (isset($data['options'])) {
                foreach ($data['options'] as $optionId => $optionName) {
                    $child = new Attribute($optionId, $optionName, Attribute::TYPE_OPTION);
                    $attr->addChild($child);
                }
            }
        }
        
        return $this->saveAttribute($attr);
    }
    
    public function deleteAttribute($attrId)
    {
        $collection = $this->fetchAll();
        $attr = $collection->findById($attrId);
        if ($attr->hasChildren()) {
            foreach ($attr->getChildren() as $child) {
                $this->deleteAttribute($child->getId());
            }
        }
        
        $this->repository->delete('product_attribute', array('attribute_id' => $attrId));
        $this->repository->delete('attribute', array('id' => $attrId));
        
        return true;
    }
    
}
