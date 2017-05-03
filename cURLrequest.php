<?php

/*Define Variables*/
    $curl = curl_init();
    $form_id = $_POST['form_id'];
    $form_id_copy =  intval($form_id + 1); //Returns the POST ID, adds 1, which represents the form createdy by copying itself.
    $token = $_POST['token'];
    $runTest = $_POST['run_test'];
    $number_test = intval($_POST['number_test']);
    $success = 0;

/*Capture all forms*/
  if (isset($_POST['all_forms']))
  {
      $url = "https://www.formstack.com/api/v2/form.json";
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.formstack.com/api/v2/form.json",
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "Authorization: Bearer $token",
          "Content-Type: application/json",
          "Accept: application/json",
        ),
      ));
    $querystring = "oauth_token=$token";
    $result = curl_exec($curl);
    $response = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  }
/*Capture just the one form*/
  if (isset($_POST['one_form']))
  {
      $url = "https://www.formstack.com/api/v2/form/$form_id.json";
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.formstack.com/api/v2/form/$form_id.json",
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "Authorization: Bearer $token",
          "Content-Type: application/json",
          "Accept: application/json",
        ),
      ));
    $querystring =  "oauth_token=$token";
    $result = curl_exec($curl);
    $response = curl_getinfo($curl, CURLINFO_HTTP_CODE);
 }
/*Copy just the one form*/
 if (isset($_POST['copy_form']))
 {
   $url = "https://www.formstack.com/api/v2/form/$form_id/copy.json";
   curl_setopt_array($curl, array(
     CURLOPT_URL => "https://www.formstack.com/api/v2/form/$form_id/copy.json",
     CURLOPT_RETURNTRANSFER => 1,
     CURLOPT_POST => 1,
     CURLOPT_HTTPHEADER => array(
       "Authorization: Bearer $token",
       "Content-Type: application/json",
       "Accept: application/json",
     ),
   ));
   $querystring = "oauth_token=$token";
   $result = curl_exec($curl);
   $response = curl_getinfo($curl, CURLINFO_HTTP_CODE);
 }
/*Delete the copy of the form that was just copied*/
 if (isset($_POST['delete_form']))
 {
   $url = "https://www.formstack.com/api/v2/form/$form_id_copy.json";
   curl_setopt_array($curl, array(
      CURLOPT_URL => "https://www.formstack.com/api/v2/form/$form_id_copy.json",
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_CUSTOMREQUEST => "DELETE",
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $token",
        "Content-Type: application/json",
        "Accept: application/json",
      )
    ));
    $querystring = "oauth_token=$token";
    $result = curl_exec($curl);
    $response = curl_getinfo($curl, CURLINFO_HTTP_CODE);
 }
/*Automated Test*/
if (isset($_POST['run_test']))
{
  for ($i = 0 ; $i < $number_test ; $i++){
    $form_id = intval($form_id);
    $increase_form_id = ++$form_id;
    $url = "https://www.formstack.com/api/v2/form/$increase_form_id.json";
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://www.formstack.com/api/v2/form/$increase_form_id.json",
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $token",
        "Content-Type: application/json",
        "Accept: application/json",
      ),
    ));
  $querystring =  "oauth_token=$token";
  $result = curl_exec($curl);
  $response = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
  /*conditional to see if response code is success*/
    if($response == 200){
        $success++;
    }
  }
  $success_rate = ($success/$number_test)*100;
}

curl_close ($curl);
?>

<!--Begin HTML of Form-->
  <!doctype html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Formstack QA Analysit Assignment</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="script.js"></script>
  </head>

  <body>
    <div>
      <h1>INSTRUCTIONS || NOTES</h1>
      <p>Below are some helpful instructions.</p>
      <ul>
        <li><b>Step 1:</b> Show all forms.  Enter your oauth token.  And then click the 1st button. </li>
        <li><b>Step 2:</b> Show just one form.  Enter your oauth token, and a form ID. Once you enter your form ID button #2 will show.  Click it.</li>
        <li><b>Step 3:</b> Copy one form.  Enter your oauth token, and a form ID. Once you enter your form ID button #3 will show.  Click it.</li>
        <li><b>Step 4:</b> Delete the copy of the form you just made. Same as before, enter oauth and form ID.  IMPORTANT: needs to be the same formID of the one you used to make a copy. Click button #4.</li>
        <li><b>Step 5:</b> Run an automated test. This will return the number of forms you specify, returning new forms that you created since you created the form with the Form ID. As before enter in oauth and a form id (choose an older one).  Then hit how many forms you'd like to retrieve.  If you don't have any forms past the one you enter, your success rate will reflect that.</li>
      </ul>
      <p><b>Notes:</b></p>
      <p>The 'delete copy' form only works for forms created recently.  For example, I have some forms I created a couple years ago and the id assigning to the new forms are different.  You should choose a form you created recently to test this.</p>
      <p>You must have a form ID that is at least 5 characters long in order for button #2-5 to show.</p>
      <p>In order for button #5 to show, you must have entered in a number for the field "Enter number of test to run"</p>
    </div>
    <form method="post">
        <ul class="form-custom">
            <li><label>Please provide your oAuthorize Token <span class="required">*</span></label>
              <input type="text" name="token" id="token" class="field-divided" placeholder="it's long..." />
              <label>Please provide your Form ID</label>
              <input type="text" name="form_id" id="form_id" class="field-divided trigger_show" placeholder="found at the end of your forms url"/>
              <label>To run an automated test enter how many forms you'd like to return</label>
              <input type="text" name="number_test" id="number_test" class="field-divided trigger_show" placeholder="Enter number of test to run (for step #5)"/>
            <li>
                <label>What would you like to do?</label>
            </li>
            <li>
                <input type="submit" id="all_forms" name="all_forms" class="button show" value="#1. Get all forms on the account" />
                <input type="submit" id="one_form" name="one_form" class="button hide" value="#2. Get just the one form" />
                <input type="submit" id="copy_form" name="copy_form" class="button hide" value="#3. Copy that one form" />
                <input type="submit" id="delete_form" name="delete_form" class="button hide" value="#4. Delete that copied form" />
                <input type="submit" id="run_test" name="run_test" class="button test" value="#5. Click here to run an automated test" />

            </li>
        </ul>
    </form>
    <div>
<!--Api Responses -->
      <?php
        if (isset($response))
          {
              if ($response == 200)
              {
                  print "<h2>REQUEST URL:</h2> $url<br><br>";
                  print "<h2>QUERY STRING:</h2> $querystring<br><br>";
                  print "<h2>RESPONSE CODE:</h2> $response<br><br>";
                  print "<h2>SUCCESS RATE (relevant to automated test):</h2> % $success_rate<br><br>";
                  print "<h2>AND HERE ARE THE JSON RESULTS:</h2> $result<br>";
                  clearstatcache();

              }elseif($response == 201) /*only for copying a form where return code is 201*/
              {
                  print "<h2>It's been copied, go check your account</h2><br><br>";
                  print "<h2>REQUEST URL:</h2> $url<br><br>";
                  print "<h2>QUERY STRING:</h2> $querystring<br><br>";
                  print "<h2>RESPONSE CODE:</h2> $response<br><br>";
                  print "<h2>AND HERE ARE THE JSON RESULTS:</h2> $result<br>";
                  clearstatcache();
              }
              else
              {
                  print "<h2>No bueno, it didn't work</h2><br><br>";
                  print "<h2>REQUEST URL:</h2> $url<br><br>";
                  print "<h2>QUERY STRING:</h2> $querystring<br><br>";
                  print "<h2>RESPONSE CODE:</h2> $response<br><br>";
                  print "<h2>Success Rate:</h2> $success<br><br>";
                  print "<h2>AND HERE ARE THE JSON RESULTS:</h2> $result<br>";
                  clearstatcache();
              }
          }
      ?>
    </div>
  </body>
</html>
