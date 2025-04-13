<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class PostResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return 
        [
            'id'        => $this->id,
            'title'     => $this->title,
            'content'   => $this->content,
            'slug'      => $this->slug,
            'excerpt'   => $this->excerpt,
            'status'    => $this->status,
            'image'     => URL::to($this->image),
            'author'    => $this->author ? [
                'name'  => $this->author->name ?? 'Unknown Author'
            ] : null,
            'category'  => $this->category ? $this->category->name : 'Uncategorized',
            'created_at'=> $this->created_at ? $this->created_at->format('M d, Y') : null
        ];
    }
}

