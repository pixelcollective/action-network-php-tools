<?php
(new class {
  public function init($app, $people)
  {
  	$this->app = $app;
  	$this->people = $people;
    
    $submissions = $this->collect($this->submissions());
    
    return $submissions->map(function ($submission) {    
        return $this->getFormFromSubmission($submission);
    })->map(function ($form) { 
        return $this->template($form);
    })->all();
  }
  
  public function submissions() {
      return $this->app->get('api.request')->request(
        $this->people
          ->request()
          ->filter(function ($person) {
            return $person->name->first == 'Kelly';
          })
          ->first()
          ->getLink('submissions')
      )
      ->embedded;
  }
  
  public function template($form)
  {
      return (object) [
        'title' => $form->title,
        'submit' => $form->links['record_submissions_helper'],
        'url' => $form->browser_url,
      ];
  }
  
  public function getFormFromSubmission($submission)
  {
    $request = 'forms/' . $submission->{"action_network:form_id"};
    return $this->app->get('api.request')->request($request);
  }
  
  public function collect($items) {
    return $this->app->get('collection')::make($items);
  }
})->init($app, $people)
