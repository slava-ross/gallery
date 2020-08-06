<main>
    <div class="forms add_photo_form">
        <h2>Новая фотография</h2>
        <form method="post" enctype="multipart/form-data">
            <p>
                <label>Фото:<br>
                    <input type="file" name="photo[]" multiple>
                </label>
            </p>
            <p>
                <input type="submit" name="submit" value="Добавить">
            </p>
                <?php
                    if (array_key_exists('errors', $vars)) {
                        print('<p>');
                        foreach ($vars['errors'] as $errorMsg) {
                            print('<p class="message error">'.$errorMsg.'</p>');
                        }
                        print('</p>');
                    }
                ?>
        </form>
    </div>
</main>