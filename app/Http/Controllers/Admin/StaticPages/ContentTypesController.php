<?php

namespace App\Http\Controllers\Admin\StaticPages;

use App\ModelStaticPages\ContentType;
use App\Http\Controllers\Controller;

class ContentTypesController extends Controller
{
    private $contentType;
    public function __construct(ContentType $contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * Get List
     * @return \Illuminate\Http\JsonResponse
     */
    public function list() {
        $list = $this->contentType->get();

        return response()->json($list, 200);
    }
}
