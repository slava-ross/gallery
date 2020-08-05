        <main>
            <div class="main_section">
                <div class="main_block">
                    <?php 
                        if ( $vars['message'] == '' ) {
                            print ('<h2>Файл: </h2>
                                <p>Описание</p>'
                            );
                        }
                        else print ( $vars['message'] );
                    ?>
                </div>
            </div>
        </main>
