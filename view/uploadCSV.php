<?php

  $upload_success = null;
  $upload_error = '';

  if (!empty($_FILES['files'])) {
    /*
      the code for file upload;
      $upload_success – becomes "true" or "false" if upload was unsuccessful;
      $upload_error – an error message of if upload was unsuccessful;
    */
  }

?>
  

  <form class="box" method="post" action="" enctype="multipart/form-data">

  <?php if ($upload_success === null): ?>

  <div class="box__input">
    <!-- ... -->
  </div>

  <?php endif; ?>

  <!-- ... -->

  <div class="box__success"<?php if( $upload_success === true ): ?> style="display: block;"<?php endif; ?>>Done!</div>
  <div class="box__error"<?php if( $upload_success === false ): ?> style="display: block;"<?php endif; ?>>Error! <span><?=$upload_error?></span>.</div>

</form>