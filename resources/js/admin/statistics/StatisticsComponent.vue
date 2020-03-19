<template>
    <div class="row">
        <Menu></Menu>
        <div class="col-md-3 col-sm-3 statistic">
            <div class="block statistic-block">
                <form action="...">
                    <div class="form-group">
                        <input type="number" name="date" class="form-control" placeholder="-- / --/ --">
                    </div>
                    <div class="form-group">
                        <input type="number" name="date2" class="form-control" placeholder="-- / --/ --">
                    </div>
                    <button type="button" class="btn-effect">Axtar</button>
                </form>
            </div>
            <div class="block statistic-block">
                <h4>who type</h4>
                <div class="checkbox">
                    <input type="checkbox" id="checkbox_0">
                    <label for="checkbox_0">Withouts pervisor</label>
                </div>
            </div>
            <div class="block statistic-block">
                <h4>asset</h4>
                <div class="checkbox">
                    <input type="checkbox" id="checkbox_1">
                    <label for="checkbox_1">Asset1 (Name 1)</label>
                </div>
            </div>
            <div class="block statistic-block">
                <h4>İcraçı şəxslər</h4>
                <div class="checkbox">
                    <input type="checkbox" id="checkbox_2">
                    <label for="checkbox_2">Name Surname</label>
                </div>
                <div class="checkbox">
                    <input type="checkbox" id="checkbox_3">
                    <label for="checkbox_3">Name Surname</label>
                </div>
                <div class="checkbox">
                    <input type="checkbox" id="checkbox_4">
                    <label for="checkbox_4">Name Surname</label>
                </div>
                <div class="checkbox">
                    <input type="checkbox" id="checkbox_5">
                    <label for="checkbox_5">Name Surname</label>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 inner-tab">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#umumi" class="block" aria-controls="umumi"
                                                          role="tab" data-toggle="tab">İcra olunan</a>
                </li>
                <li role="presentation"><a href="#invoice" class="block" aria-controls="man" role="tab"
                                           data-toggle="tab">İnvoysu olmayan</a>
                </li>
                <li role="presentation"><a href="#tamamlanmish" class="block" aria-controls="woman" role="tab"
                                           data-toggle="tab">Tamamlanmış</a>
                </li>
                <li role="presentation"><a href="#sebet" class="block" aria-controls="cari" role="tab"
                                           data-toggle="tab">Səbətdə qalan</a>
                </li>
            </ul>
            <div class="tab-content qeydiyyat">
                <div role="tabpanel" class="tab-pane fade in active" id="umumi">
                    <div class="right-side col-xs-12">
                        <div class="block table-block">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>№</th>
                                    <th>Description</th>
                                    <th>Asset</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>01</td>
                                    <td>Description</td>
                                    <td>Name</td>
                                    <td>New</td>
                                </tr>
                                <tr>
                                    <td>02</td>
                                    <td>Description</td>
                                    <td>Name</td>
                                    <td>New</td>
                                </tr>
                                <tr>
                                    <td>03</td>
                                    <td>Description</td>
                                    <td>Name</td>
                                    <td>New</td>
                                </tr>
                                </tbody>
                            </table>
                            <nav>
                                <ul class="pagination">
                                    <li class="disabled"><a href="#" aria-label="Previous"><span
                                            aria-hidden="true">ƏVVƏL</span></a></li>
                                    <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">4</a></li>
                                    <li><a href="#">5</a></li>
                                    <li>
                                        <a href="#" aria-label="Next">
                                            <span aria-hidden="true">NÖVBƏTİ</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="invoice">
                    <div class="right-side col-xs-12">
                        <div class="block table-block">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>№</th>
                                    <th>Description</th>
                                    <th>Asset</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>01</td>
                                    <td>Description</td>
                                    <td>Name</td>
                                    <td>New</td>
                                </tr>
                                <tr>
                                    <td>02</td>
                                    <td>Description</td>
                                    <td>Name</td>
                                    <td>New</td>
                                </tr>
                                <tr>
                                    <td>03</td>
                                    <td>Description</td>
                                    <td>Name</td>
                                    <td>New</td>
                                </tr>
                                </tbody>
                            </table>
                            <nav>
                                <ul class="pagination">
                                    <li class="disabled"><a href="#" aria-label="Previous"><span
                                            aria-hidden="true">ƏVVƏL</span></a></li>
                                    <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">4</a></li>
                                    <li><a href="#">5</a></li>
                                    <li>
                                        <a href="#" aria-label="Next">
                                            <span aria-hidden="true">NÖVBƏTİ</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tamamlanmish">
                    <div class="right-side col-xs-12">
                        <div class="block table-block">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>№</th>
                                    <th>Description</th>
                                    <th>Asset</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>01</td>
                                    <td>Description</td>
                                    <td>Name</td>
                                    <td>New</td>
                                </tr>
                                <tr>
                                    <td>02</td>
                                    <td>Description</td>
                                    <td>Name</td>
                                    <td>New</td>
                                </tr>
                                <tr>
                                    <td>03</td>
                                    <td>Description</td>
                                    <td>Name</td>
                                    <td>New</td>
                                </tr>
                                </tbody>
                            </table>
                            <nav>
                                <ul class="pagination">
                                    <li class="disabled"><a href="#" aria-label="Previous"><span
                                            aria-hidden="true">ƏVVƏL</span></a></li>
                                    <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">4</a></li>
                                    <li><a href="#">5</a></li>
                                    <li>
                                        <a href="#" aria-label="Next">
                                            <span aria-hidden="true">NÖVBƏTİ</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="sebet">
                    <div class="right-side col-xs-12">
                        <div class="block table-block">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>№</th>
                                    <th>Description</th>
                                    <th>Asset</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>01</td>
                                    <td>Description</td>
                                    <td>Name</td>
                                    <td>New</td>
                                </tr>
                                <tr>
                                    <td>02</td>
                                    <td>Description</td>
                                    <td>Name</td>
                                    <td>New</td>
                                </tr>
                                <tr>
                                    <td>03</td>
                                    <td>Description</td>
                                    <td>Name</td>
                                    <td>New</td>
                                </tr>
                                </tbody>
                            </table>
                            <nav>
                                <ul class="pagination">
                                    <li class="disabled"><a href="#" aria-label="Previous"><span
                                            aria-hidden="true">ƏVVƏL</span></a></li>
                                    <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">4</a></li>
                                    <li><a href="#">5</a></li>
                                    <li>
                                        <a href="#" aria-label="Next">
                                            <span aria-hidden="true">NÖVBƏTİ</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-3 statistic statistic-more">
            <div class="block statistic-block">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#etrafli" aria-controls="umumi"
                                                              role="tab" data-toggle="tab">Ətraflı</a>
                    </li>
                    <li role="presentation"><a href="#tarixce" aria-controls="man" role="tab"
                                               data-toggle="tab">Tarixçə</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="etrafli">
                        <ul>
                            <li>
                                Created date/time
                                <b>2019-02-25 21:00</b>
                            </li>
                            <li>
                                Created by
                                <b>executor</b>
                            </li>
                            <li>
                                Work order type
                                <b>Without services</b>
                            </li>
                            <li>
                                Work order number
                                <b>6956895</b>
                            </li>
                            <li>
                                Requested by
                                <b>Superviser</b>
                            </li>
                            <li>
                                Asset
                                <b>Name</b>
                            </li>
                        </ul>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="tarixce">
                        <ul>
                            <li>
                                Created date/time
                                <b>2019-02-25 21:00</b>
                            </li>
                            <li>
                                Created by
                                <b>executor</b>
                            </li>
                            <li>
                                Work order type
                                <b>Without services</b>
                            </li>
                            <li>
                                Work order number
                                <b>6956895</b>
                            </li>
                            <li>
                                Requested by
                                <b>Superviser</b>
                            </li>
                            <li>
                                Asset
                                <b>Name</b>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Menu from './components/MenuComponent'
    export default {
        name: "StatisticsComponent",
        components: {
            Menu
        }
    }
</script>

<style scoped>

</style>