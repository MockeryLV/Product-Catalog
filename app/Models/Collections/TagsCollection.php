<?php

namespace App\Models\Collections;


use App\Models\Tag;

class TagsCollection
{


    private array $tags = [];

    public function __construct(array $tags)
    {
        foreach ($tags as $tag) {
            if ($tag instanceof Tag) {
                $this->tags[] = $tag;
            }
        }
    }


    public function getTags(): array
    {
        return $this->tags;
    }

}