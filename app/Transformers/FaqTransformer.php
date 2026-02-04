<?php

namespace App\Transformers;

use App\Models\Faq;
use League\Fractal\TransformerAbstract;

class FaqTransformer extends TransformerAbstract
{

    /**
     * Transform object data
     *
     * @param Faq $faq
     * @return array
     */
    public function transform(Faq $faq): array
    {
        return $faq->toArray();        
        
    }
}