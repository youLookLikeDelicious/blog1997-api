<?php
namespace App\Repository;

use App\Model\SocialAccount as ModelSocialAccount;

class SocialAccount
{
    /**
     * SocialAccount model
     *
     * @var ModelSocialAccount
     */
    protected $model;

    /**
     * Create instance
     *
     * @param ModelSocialAccount $model
     */
    public function __construct(ModelSocialAccount $model)
    {
        $this->model = $model;
    }

    /**
     * Get social account record
     *
     * @param string $foreignId
     * @param string $type
     * @return ModelSocialAccount|null
     */
    public function find($foreignId, $type)
    {
        $record = $this->model
            ->where('foreign_id', $foreignId)
            ->where('type', $type)
            ->first();
        
        return $record;
    }
}