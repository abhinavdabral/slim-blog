<?php

namespace App\ToHTML;

use App\Models\User;
use App\Models\Post;
use Michelf\Markdown;
use Carbon\Carbon;

class ToHTML {

    protected $themeLocation;
    protected $container;

    public function __construct($container, $themeLocation = null){
        if($themeLocation == null)
            $this->themeLocation = dirname(__DIR__).'/../resources/themes/default';
        else
            $this->themeLocation = dirname(__DIR__).'/../resources/themes/'.$themeLocation;

        $this->container = $container;

    }

    public function getTheme(){
        return $this->themeLocation;
    }

    public function fromPost(Post $post)
    {
        $pageTemplate = $this->themeLocation.'/post.php';
        $args = [
            'title'     => $post->title,
            'slug'      => $post->slug,
            'content'   => Markdown::defaultTransform($post->content),
            'author'    => $post->user->name,
            'created_at'=> Carbon::parse($post->created_at)->format('l, jS \\of F Y'),
            'updated_at'=> Carbon::parse($post->updated_at)->toDateString()
        ];

        $string = (function() use($args, $pageTemplate) {
            
            ob_start();
            include($pageTemplate);
            $var=ob_get_clean(); 
            ob_end_clean();

            return $var;;
        })();
        
        return $string;
    }

    public function homepage()
    {
        $pageTemplate = $this->themeLocation.'/home.php';
        $args = [
            'title'     => "Home",
            'posts'      => Post::with('user:id,name')->get()->toArray()
        ];

        $string = (function() use($args, $pageTemplate) {
            
            ob_start();
            include($pageTemplate);
            $var=ob_get_clean(); 
            ob_end_clean();

            return $var;;
        })();
        
        return $string;
    }

    public function savePage($slug, $string)
    {
        $path_to_file = dirname(__DIR__).'/../public/'.$slug.".html";
        $handle = fopen($path_to_file, "w");
        if($handle==NULL){
            return NULL;
        }
        
        fwrite($handle, $string);
        fclose($handle);
        return $this->container->baseURL.'/../'.$slug.'.html';

    }


}