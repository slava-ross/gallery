<main>
    <div class="forms add_photo_form">
        <h2>Новая фотография</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <p>
                <input type="file" name="photo[]" multiple required accept="image" title="Необходимо выбрать минимум один файл">
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
                    if (array_key_exists('messages', $vars)) {
                        print('<p>');
                        foreach ($vars['messages'] as $message) {
                            print('<p class="message">'.$message.'</p>');
                        }
                        print('</p>');
                    }

                ?>
        </form>
    </div>
</main>