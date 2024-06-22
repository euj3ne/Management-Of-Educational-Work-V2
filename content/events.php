<?php include '../includes/header.php'; ?>
<section style="margin: 17px;">
        <div class="container">
            <div>
                <div class="row">
                    <div class="col">
                        <div class="card" style="background: rgb(52,47,112);">
                            <div class="card-body">
                                <h4 class="card-title" style="color: rgb(255,255,255);">Название</h4>
                                <h6 class=" card-subtitle mb-2" style="color: rgb(255,255,255);">Тематика</h6>
                                <p class="card-text" style="color: rgb(255,255,255);">Текст</p>
                                <a class="btn btn-primary shadow" role="button" href="login.html" data-bs-toggle="modal" data-bs-target="#modal-view" style="background: rgb(66,70,133);border-color: #ffffff00;--bs-primary: #5a5fb3;--bs-primary-rgb: 90,95,179;">Читать далее</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- Модальное окно для просмотра -->
<div class="modal fade" role="dialog" tabindex="-1" id="modal-view">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="color: rgb(0,0,0);">Просмотр (GET)</h4>
                <button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="color: rgb(0,0,0);margin-bottom: -1px;">Название</p><input type="text" name="surname" style="padding-right: 232px;margin-bottom: 25px;" readonly>
                <p style="color: rgb(0,0,0);margin-bottom: -1px;">Описание</p><input type="text" name="name" style="padding-right: 232px;margin-bottom: 25px;" readonly>
                <p style="color: rgb(0,0,0);margin-bottom: -1px;">Подробное описание</p><textarea rows="10" style="padding-right: 232px;margin-bottom: 25px;" readonly></textarea>
                <p style="color: rgb(0,0,0);margin-bottom: -1px;">Картинка</p><input type="file">
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>



    <?php include '../includes/footer.php'; ?>