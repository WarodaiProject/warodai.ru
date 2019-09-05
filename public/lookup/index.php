<?php include('../_inc/header.php') ?>
        <div>
            <h3>Поиск по японско-русскому электронному словарю</h3>

            <div style="float:left">
                <form onsubmit="return false">
                    <input type="text" onkeydown="initLookup(event)" name="keyword" id="keyword">
                    <input type="button" onclick="lookup()" value="найти">

                    <div style="width:300px">Введите японское или русское слово.<br/>
                        Используйте символ "*" для поиска слов, заканчивающихся или начинающихся с заданной строки
                        (напр. "実*")
                    </div>
                </form>
            </div>
            <div style="float:right;width:300px">
                <strong>Внимание!</strong> Если вы заметили ошибку в результатах поиска,
                выделите ее и нажмите <strong>Cntrl+Enter</strong>. С помощью открывшейся формы вы сможете быстро
                отослать отчет об ошибке редакторам словаря.
            </div>
        </div>
        <div id="results" style="clear:both"></div>
<?php include('../_inc/footer.php') ?>