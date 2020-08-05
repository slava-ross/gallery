<main>
    <h2>Фотоальбом</h2>
    <div class="container">
        <div class="cards">

        <?php if ( count( $vars['errorMessages'] ) == 0 ): ?>
            <?php
                foreach ($vars['photoArray'] as $photo) {
                    print('<a href="index.php?page=show_photo&imageName=' . $photo['photoFileName'] . '" class="card">');
                    print('    <img src="thumbs/' . $photo['thumbFileName'] . '" alt="image" class="card-image">');
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