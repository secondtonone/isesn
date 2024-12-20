<div class="header">
    	<div class="navbar">
        	<div class="container">
            	<ul class="nav">
                	<li>
                    	<div class="nav-item panel"><i class="icon icon-panel"></i>Рабочий стол</div>
                    </li>
                    <li>
                    	<div class="nav-item journal"><i class="icon icon-journal"></i>Журнал действий</div>
                    </li>
                    <li>
                    	<div class="active stats"><i class="icon icon-stats"></i>Аналитика</div>
                    </li>
                    <li>
                        <div class="nav-item help"><i class="icon icon-help"></i>Помощь</div>
                    </li>
                </ul>
                <div class="exit"><i class="icon icon-exit"></i>Выход</div>
            </div>
        </div>
    </div>
    <div class="page-content">
    <div class="stat-container">
        <div class="caption"><i class="icon line-chart"></i>Динамика продаж объектов по категориям за весь год</div>
        <div class="canvas-holder" id="year-sells-objects-canvas-wrapper">
            <canvas id="year-sells-objects"></canvas>
        </div>
        <div class="legend-warapper">
            <div id="legend-year-sells-objects"></div>
            <div class="stat-control">
                <label>Год:
                    <select id="change-year-sells-objects" class="year">
                    </select>
                </label>
            </div>
        </div>
    </div>
        <div class="stat-container">
            <div class="caption"><i class="icon pie-chart"></i>Кол-во продаж объектов по категориям</div>
            <div class="canvas-holder-pie">
                <h4>Спрос и предложение</h4>
                <div id="year-sells-objects-radar-canvas-wrapper">
                    <canvas  id="year-sells-objects-radar"></canvas>
                </div>
                <div class="stat-control">
                    <label>Год:
                        <select id="change-year-sells-objects-radar" class="year">
                        </select>
                    </label>
                </div>
            </div>
            <div class="canvas-holder-pie">
                <h4>За весь год</h4>
                <div id="year-sells-objects-pie-canvas-wrapper">
                    <canvas  id="year-sells-objects-pie"></canvas>
                </div>
                <div class="stat-control">
                    <label>Год:
                        <select id="change-year-sells-objects-pie" class="year">
                        </select>
                    </label>
                </div>
            </div>
            <div class="canvas-holder-pie">
                <h4>За месяц</h4>
                <div id="month-sells-objects-pie-canvas-wrapper">
                    <canvas  id="month-sells-objects-pie"></canvas>
                </div>
                <div class="stat-control">
                    <label>Месяц:
                        <select id="change-month-sells-objects-pie" class="month">
                            <option value="1">Январь</option>
                            <option value="2">Февраль</option>
                            <option value="3">Март</option>
                            <option value="4">Апрель</option>
                            <option value="5">Май</option>
                            <option value="6">Июнь</option>
                            <option value="7">Июль</option>
                            <option value="8">Август</option>
                            <option value="9">Сентябрь</option>
                            <option value="10">Октябрь</option>
                            <option value="11">Ноябрь</option>
                            <option value="12">Декабрь</option>
                        </select>
                    </label>
                </div>
            </div>
            <div class="legend-warapper">
                <div id="legend-sells-objects-pie"></div>
            </div>
        </div>
        <div class="stat-container">
            <div class="caption"><i class="icon line-chart"></i>Динамика цен объектов по категориям</div>
            <div class="canvas-holder" id="year-price-objects-canvas-wrapper">
                <canvas id="year-price-objects"></canvas>
            </div>
            <div class="legend-warapper">
                <div id="legend-year-price-objects"></div>
                <div class="stat-control">
                    <label>Год:
                        <select id="change-year-price-objects" class="year">
                        </select>
                    </label>
                </div>
            </div>
        </div>
        <div class="stat-container">
            <div class="caption"><i class="icon area-chart"></i>Динамика роста БД</div>
                <!-- <div class="table-wrapper">
                    <h4>Всего записей в БД</h4>
                    <table>
                        <tbody>
                            <tr><td class="table-sub-title" colspan="2">Объекы недвижимости</td></tr>
                            <tr><td>Всего:</td><td class="table-value" id="objects-all"></td></tr>
                            <tr><td>В продаже:</td><td class="table-value" id="objects-selling"></td></tr>
                            <tr><td>Продано:</td><td class="table-value" id="objects-sells-out"></td></tr>
                            <tr><td>Снято с продажи:</td><td class="table-value" id="objects-hide-out"></td></tr>
                            <tr><td>Не закрепленных:</td><td title="Рекомендованная величина:0" class="table-value" id="objects-unattached"></td></tr>
                            <tr><td class="table-sub-title" colspan="2">Покупателей</td></tr>
                            <tr><td>Всего:</td><td class="table-value" id="clients-all"></td></tr>
                            <tr><td>Активных:</td><td class="table-value" id="clients-active"></td></tr>
                            <tr><td>Не активных:</td><td class="table-value" id="clients-disactive"></td></tr>
                            <tr><td>Не закрепленных:</td><td title="Рекомендованная величина:0" class="table-value" id="clients-unattached"></td></tr>
                            <tr><td class="table-sub-title" colspan="2">Пользователей</td></tr>
                            <tr><td>Всего:</td><td class="table-value"  id="users-all"></td></tr>
                            <tr><td>Активных:</td><td class="table-value" id="users-active"></td></tr>
                            <tr><td>Не активных:</td><td title="Рекомендованная величина:0" class="table-value" id="users-disactive"></td></td>
                        </tbody>
                    </table>
                </div>
                <div class="table-wrapper">
                    <h4>Активность пользователей</h4>
                    <table>
                        <tbody>
                            <tr><td class="table-sub-title" colspan="2">Записей в день за последний месяц</td></tr>
                            <tr><td>Всего:</td><td title="Рекомендованная величина:5" class="table-value" id="all-records"></td></tr>
                            <tr><td>Объекты:</td><td title="Рекомендованная величина:3" class="table-value" id="objects-records"></td></tr>
                            <tr><td>Покупатели:</td><td title="Рекомендованная величина:2" class="table-value" id="clients-records"></td></tr>
                            <tr><td class="table-sub-title" colspan="2">Коэфицент продаж</td></tr>
                            <tr><td>За 7 дней:</td><td title="Рекомендованная величина:5" class="table-value" id="week-sell-outs"></td></tr>
                            <tr><td>За 30 дней:</td><td title="Рекомендованная величина:25" class="table-value" id="month-sell-outs"></td></tr>
                            <tr><td>За 60 дней:</td><td title="Рекомендованная величина:45" class="table-value" id="monthplus-sell-outs"></td></tr>
                            <tr><td class="table-sub-title" colspan="2">Количество посещений</td></tr>
                            <tr><td>За сегодня:</td><td title="Рекомендованная величина:10" class="table-value" id="today-visits"></td></tr>
                            <tr><td>За 7 дней:</td><td title="Рекомендованная величина:70" class="table-value" id="week-visits"></td></tr>
                            <tr><td>За 30 дней:</td><td title="Рекомендованная величина:210" class="table-value" id="month-visits"></td></tr>
                        </tbody>
                    </table>
                </div> -->
                <div class="canvas-holder-dynamic">
                    <!-- <h4>Динамика роста БД</h4> -->
                    <div id="year-dynamic-db-canvas-wrapper">
                        <canvas  id="year-dynamic-db"></canvas>
                    </div>
                    <div class="stat-control">
                        <label>Год:
                            <select id="change-year-dynamic-db" class="year">
                            </select>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>