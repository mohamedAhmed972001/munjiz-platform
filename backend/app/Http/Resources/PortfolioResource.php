<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  // app/Http/Resources/PortfolioResource.php

  public function toArray($request): array
  {
    return [
      'id'          => $this->id,
      'title'       => $this->title,
      'description' => $this->description,
      'link'        => $this->link,
      // دي أهم ميثود في الباكدج بترجع رابط الصورة كامل للـ Frontend
      'image_url'   => $this->getFirstMediaUrl('portfolio_images'),
      'created_at'  => $this->created_at->format('Y-m-d'),
    ];
  }
}
