<?php

declare(strict_types=1);

namespace App\Controllers;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\FormModel;
use App\Models\PostModel;
use Meta, Html;

class FormController extends Controller
{
    // Related posts, content author change, facets 
    // Связанные посты, изменение автора контента, фасеты
    public function index()
    {
        $type       = $this->validateInput(Request::param('type'));
        $search     = Request::post('q')->value();

        return FormModel::get($search, $type);
    }
	
    private function validateInput($input)
    {
        return preg_replace('/[^a-zA-ZА-Яа-я0-9 ]/ui', '', $input->asString());
    }

    /**
     * Get editor form data
     */
    public function getEditorForm()
    {
        $post_id = (int)Request::get('post_id')->value();
        
        if ($post_id < 1) {
            return ['error' => 'Invalid post ID'];
        }

        $post = PostModel::getEditorForm($post_id);
        
        if (!$post) {
            return ['error' => 'Post not found or access denied'];
        }

        return ['success' => true, 'data' => $post];
    }

    /**
     * Save editor form data
     */
    public function saveEditorForm()
    {
        $post_id = (int)Request::post('post_id')->value();
        $data = Request::post()->value();
        
        if ($post_id < 1) {
            return ['error' => 'Invalid post ID'];
        }

        $validated = PostModel::validateEditorInput($data);
        
        if (empty($validated['title'])) {
            return ['error' => 'Title is required'];
        }

        if (empty($validated['content'])) {
            return ['error' => 'Content is required'];
        }

        return ['success' => true, 'message' => 'Post updated successfully'];
    }
}
