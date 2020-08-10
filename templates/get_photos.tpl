<main>
    <h2>Фотоальбом</h2>
    <div class="container">
        <div class="cards">

        <?php if (count( $vars['errorMessages']) == 0 ): ?>
            <?php
                foreach ($vars['photoArray'] as $photoFileName) {
                    print('<a href="index.php?page=show_photo&imageName=' . $photoFileName . '" class="card">');
                    print('    <img src="thumbs/thumb_' . $photoFileName . '" alt="photo: ' . $photoFileName . '" class="card-image">');
                    print('</a>');
                }
            ?>

        </div>
    </div>
    <?php
        else:
            foreach ( $vars['errorMessages'] as $errorMsg ) {
                print('<p class="message error">'.$errorMsg.'</p>');
            }
        endif;
    ?>
</main>