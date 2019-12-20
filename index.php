<?php include 'catalog.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Каталог</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/graphite.css">

    <script src="js/jquery-1.9.0.min.js"></script>

    <script src="js/bootstrap.js"></script>
</head>
<body>

<div class="modal fade" id="addModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">ТОВАР</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body px-4">
                <div class="table-responsive" id="employee_details">
                    <table class="table table-bordered">
                        <tr>
                            <td width="10%" align="right"><b>наименование</b></td>
                            <td width="90%"><span id="title"></span></td>
                        </tr>
                        <tr>
                            <td width="10%" align="right"><b>цена</b></td>
                            <td width="90%"><span id="price"></span>грн</td>
                        </tr>
                        <tr>
                            <td width="10%" align="right"><b>дата</b></td>
                            <td width="90%"><span id="date"></span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="wrapper">
    <div class="sidebar">
        <div class="vertmenu">
            <ul class="accordion" id="accordion">
                <?php echo $categories_menu ?>
            </ul>
        </div>
    </div>
    <div class="breadcrumbs123">
    </div>

    <div class="cat_content">
    </div>
    <select id="sort_by" style="float: right;">
        <option value="" disabled selected>выберите</option>
        <option value="sort-asc" id="sort-asc">наименьшая цена</option>
        <option value="sort-new" id="sort-new">новинки</option>
        <option value="sort-Alfavit" id="sort-Alfavit">по алфавиту</option>
    </select>
    <br />

    <div style="float: right;" class="content">
    </div>
</div>

<script src="js/jquery.dcjqaccordion.2.7.js"></script>
<script src="js/jquery.cookie.js"></script>
<script src="js/scripts.js"></script>
<script>

</script>
</body>
</html>