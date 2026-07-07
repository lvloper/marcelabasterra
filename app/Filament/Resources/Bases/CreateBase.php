<?php

namespace App\Filament\Resources\Bases;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Traits\HandlesExternalImages;

class CreateBase extends CreateRecord
{
    use HandlesExternalImages;
    
    protected function handleRecordCreation(array $data): Model
    {
        // Process external image URLs and download them locally
        $data = $this->processExternalImagesInData($data);
        
        // Ensure image fields are always strings, not arrays
        $imageFields = ['image', 'image_mobile', 'photo', 'picture', 'avatar', 'profile_photo'];
        foreach ($imageFields as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $data[$field] = $data[$field][0] ?? null;
            }
        }

        // Also normalize image fields inside block data
        if (isset($data['blocks']) && is_array($data['blocks'])) {
            $blockImageFields = ['image', 'image_mobile', 'profile_photo', 'photo'];
            foreach ($data['blocks'] as $bk => $block) {
                if (!isset($block['data']) || !is_array($block['data'])) continue;
                foreach ($blockImageFields as $bf) {
                    if (isset($block['data'][$bf]) && is_array($block['data'][$bf])) {
                        $data['blocks'][$bk]['data'][$bf] = $block['data'][$bf][0] ?? null;
                    }
                }
            }
        }
        
        $record = static::getModel()::create($data);
        
        $routeData = $data['route'] ?? [];
        $modelClass = get_class($record);

        if (method_exists($record, 'getDefaultRouteParentId')) {
            $routeData['parent_id'] = $record->getDefaultRouteParentId();
        }
        if (method_exists($record, 'getDefaultRouteLayout')) {
            $routeData['layout'] = $record->getDefaultRouteLayout();
        }
        
        $record->route()->create($routeData);

        return $record;
    }
}
