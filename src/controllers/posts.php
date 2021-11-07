<?php

class Posts extends Controller {
    public $post = null;
    public $topic;
    public $breadcrumb;
    public function manage()
    {
        Output::i()->add(Template::i()->renderTemplate('topic', [
            'topic' => $this->topic,
        ]));
    }

    public function edit() {
        if ($this->post) {
            Output::i()->title = 'Edytuj post w' . $this->topic->title;
            $form = Post::form($this->post);
            if ($form->isSuccess()) {
                $this->post->apply($form->getValues());
                Output::i()->redirect($this->post->url());
            } else {
                Output::i()->add(Template::i()->renderTemplate('edit', [
                    'post' => $this->post,
                    'form' => $form
                ]));
            }
        }
    }

    public function execute()
    {
        if (isset(Request::i()->post_id) && ctype_digit(Request::i()->post_id)) {
            $this->post = Post::load(Request::i()->post_id);
        }

        if ($this->post == null) {
            Output::i()->redirect('?');
        }
        //Output::i()->title = $this->topic->title;
        $this->topic = $this->post->topic();

        $forum = $this->topic->forum();
        $this->breadcrumb = [
            [
                'name'=> $forum->name,
                'url'=> $forum->url(),
            ],
            [
                'name'=> $this->topic->title,
                'url'=> $this->topic->url(),
            ]
        ];
    }
}