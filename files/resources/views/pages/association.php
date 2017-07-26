        <div class="content page-container association hidden">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card">
                            <div id="table-association-run" class="header table-association" data-table="run">
                                <h4 class="title">Real Users Normal
                                    <span class="events-number"></span>
                                    <script class="template-events-number" type="text/template7">
                                        <small class="pull-right">{{number}} events found</small>
                                    </script>
                                </h4>
                                <div class="row selection-param">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label>Provider</label>
                                            <select class="form-control select-provider"></select>
                                            <script class="template-select-provider" type="text/template7">
                                               <option value=""> -- all -- </option>
                                               {{#each tipsters}}
                                               <option value="{{provider}}">{{provider}} </option>
                                               {{/each}}
                                            </script>
                                        </div>
                                        <div class="col-md-3">
                                            <label>League</label>
                                            <select class="form-control select-league"></select>
                                            <script class="template-select-league" type="text/template7">
                                               <option value=""> -- all -- </option>
                                               {{#each leagues}}
                                               <option value="{{league}}">{{league}} </option>
                                               {{/each}}
                                            </script>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Odd From</label>
                                            <select class="form-control select-minOdd">
                                                <option value=""> -- all -- </option>
                                                <option value="1"> >= 1 </option>
                                                <option value="1.5"> >= 1.5 </option>
                                                <option value="2"> >= 2 </option>
                                                <option value="2.5"> >= 2.5 </option>
                                                <option value="3"> >= 3 </option>
                                                <option value="3.5"> >= 3.5 </option>
                                                <option value="4"> >= 4 </option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Odd To</label>
                                            <select class="form-control select-maxOdd">
                                                <option value=""> -- all -- </option>
                                                <option value="1.5"> <= 1.5 </option>
                                                <option value="2"> <= 2 </option>
                                                <option value="2.5"> <= 2.5 </option>
                                                <option value="3"> <= 3 </option>
                                                <option value="3.5"> <= 3.5 </option>
                                                <option value="4"> <= 4 </option>
                                                <option value="4.5"> <= 4.5 </option>
                                                <option value="5"> <= 5 </option>
                                                <option value="5.5"> <= 5.5 </option>
                                                <option value="6"> <= 6 </option>
                                                <option value="10"> <= 10 </option>
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <label>&nbsp</label>
                                            <button class="btn btn-info btn-fill pull-right modal-get-event">Go</button>
                                        </div>
                                        <div class="col-md-1">
                                            <label>&nbsp</label>
                                            <button class="btn btn-success btn-fill pull-right refresh-event-info">
                                                <i class="pe-7s-refresh"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Salary</th>
                                        <th>Country</th>
                                        <th>City</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Dakota Rice</td>
                                            <td>$36,738</td>
                                            <td>Niger</td>
                                            <td>Oud-Turnhout</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Minerva Hooper</td>
                                            <td>$23,789</td>
                                            <td>Curaçao</td>
                                            <td>Sinaai-Waas</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="card">
                            <div id="table-association-ruv" class="header table-association" data-table="ruv">
                                <h4 class="title">Real Users Vip
                                    <span class="events-number"></span>
                                    <script class="template-events-number" type="text/template7">
                                        <small class="pull-right">{{number}} events found</small>
                                    </script>
                                </h4>
                                <div class="row selection-param">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label>Provider</label>
                                            <select class="form-control select-provider"></select>
                                            <script class="template-select-provider" type="text/template7">
                                               <option value=""> -- all -- </option>
                                               {{#each tipsters}}
                                               <option value="{{provider}}">{{provider}} </option>
                                               {{/each}}
                                            </script>
                                        </div>
                                        <div class="col-md-3">
                                            <label>League</label>
                                            <select class="form-control select-league"> </select>
                                            <script class="template-select-league" type="text/template7">
                                               <option value=""> -- all -- </option>
                                               {{#each leagues}}
                                               <option value="{{league}}">{{league}} </option>
                                               {{/each}}
                                            </script>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Odd From</label>
                                            <select class="form-control select-minOdd">
                                                <option value=""> -- all -- </option>
                                                <option value="1"> >= 1 </option>
                                                <option value="1.5"> >= 1.5 </option>
                                                <option value="2"> >= 2 </option>
                                                <option value="2.5"> >= 2.5 </option>
                                                <option value="3"> >= 3 </option>
                                                <option value="3.5"> >= 3.5 </option>
                                                <option value="4"> >= 4 </option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Odd To</label>
                                            <select class="form-control select-maxOdd">
                                                <option value=""> -- all -- </option>
                                                <option value="1.5"> <= 1.5 </option>
                                                <option value="2"> <= 2 </option>
                                                <option value="2.5"> <= 2.5 </option>
                                                <option value="3"> <= 3 </option>
                                                <option value="3.5"> <= 3.5 </option>
                                                <option value="4"> <= 4 </option>
                                                <option value="4.5"> <= 4.5 </option>
                                                <option value="5"> <= 5 </option>
                                                <option value="5.5"> <= 5.5 </option>
                                                <option value="6"> <= 6 </option>
                                                <option value="10"> <= 10 </option>
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <label>&nbsp</label>
                                            <button class="btn btn-info btn-fill pull-right modal-get-event">Go</button>
                                        </div>
                                        <div class="col-md-1">
                                            <label>&nbsp</label>
                                            <button class="btn btn-success btn-fill pull-right refresh-event-info">
                                                <i class="pe-7s-refresh"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Salary</th>
                                        <th>Country</th>
                                        <th>City</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Dakota Rice</td>
                                            <td>$36,738</td>
                                            <td>Niger</td>
                                            <td>Oud-Turnhout</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Minerva Hooper</td>
                                            <td>$23,789</td>
                                            <td>Curaçao</td>
                                            <td>Sinaai-Waas</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card">
                            <div id="table-association-nun" class="header table-association" data-table="nun">
                                <h4 class="title">No Users Normal
                                    <span class="events-number"></span>
                                    <script class="template-events-number" type="text/template7">
                                        <small class="pull-right">{{number}} events found</small>
                                    </script>
                                </h4>
                                <div class="row selection-param">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label>Provider</label>
                                            <select class="form-control select-provider"></select>
                                            <script class="template-select-provider" type="text/template7">
                                               <option value=""> -- all -- </option>
                                               {{#each tipsters}}
                                               <option value="{{provider}}">{{provider}} </option>
                                               {{/each}}
                                            </script>
                                        </div>
                                        <div class="col-md-3">
                                            <label>League</label>
                                            <select class="form-control select-league"></select>
                                            <script class="template-select-league" type="text/template7">
                                               <option value=""> -- all -- </option>
                                               {{#each leagues}}
                                               <option value="{{league}}">{{league}} </option>
                                               {{/each}}
                                            </script>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Odd From</label>
                                            <select class="form-control select-minOdd">
                                                <option value=""> -- all -- </option>
                                                <option value="1"> >= 1 </option>
                                                <option value="1.5"> >= 1.5 </option>
                                                <option value="2"> >= 2 </option>
                                                <option value="2.5"> >= 2.5 </option>
                                                <option value="3"> >= 3 </option>
                                                <option value="3.5"> >= 3.5 </option>
                                                <option value="4"> >= 4 </option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Odd To</label>
                                            <select class="form-control select-maxOdd">
                                                <option value=""> -- all -- </option>
                                                <option value="1.5"> <= 1.5 </option>
                                                <option value="2"> <= 2 </option>
                                                <option value="2.5"> <= 2.5 </option>
                                                <option value="3"> <= 3 </option>
                                                <option value="3.5"> <= 3.5 </option>
                                                <option value="4"> <= 4 </option>
                                                <option value="4.5"> <= 4.5 </option>
                                                <option value="5"> <= 5 </option>
                                                <option value="5.5"> <= 5.5 </option>
                                                <option value="6"> <= 6 </option>
                                                <option value="10"> <= 10 </option>
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <label>&nbsp</label>
                                            <button class="btn btn-info btn-fill pull-right modal-get-event">Go</button>
                                        </div>
                                        <div class="col-md-1">
                                            <label>&nbsp</label>
                                            <button class="btn btn-success btn-fill pull-right refresh-event-info">
                                                <i class="pe-7s-refresh"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Salary</th>
                                        <th>Country</th>
                                        <th>City</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Dakota Rice</td>
                                            <td>$36,738</td>
                                            <td>Niger</td>
                                            <td>Oud-Turnhout</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Minerva Hooper</td>
                                            <td>$23,789</td>
                                            <td>Curaçao</td>
                                            <td>Sinaai-Waas</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card">
                            <div id="table-association-nuv" class="header table-association" data-table="nuv">
                                <h4 class="title">No Users Vip
                                    <span class="events-number"></span>
                                    <script class="template-events-number" type="text/template7">
                                        <small class="pull-right">{{number}} events found</small>
                                    </script>
                                </h4>
                                <div class="row selection-param">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label>Provider</label>
                                            <select class="form-control select-provider"></select>
                                            <script class="template-select-provider" type="text/template7">
                                               <option value=""> -- all -- </option>
                                               {{#each tipsters}}
                                               <option value="{{provider}}">{{provider}} </option>
                                               {{/each}}
                                            </script>
                                        </div>
                                        <div class="col-md-3">
                                            <label>League</label>
                                            <select class="form-control select-league"></select>
                                            <script class="template-select-league" type="text/template7">
                                               <option value=""> -- all -- </option>
                                               {{#each leagues}}
                                               <option value="{{league}}">{{league}} </option>
                                               {{/each}}
                                            </script>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Odd From</label>
                                            <select class="form-control select-minOdd">
                                                <option value=""> -- all -- </option>
                                                <option value="1"> >= 1 </option>
                                                <option value="1.5"> >= 1.5 </option>
                                                <option value="2"> >= 2 </option>
                                                <option value="2.5"> >= 2.5 </option>
                                                <option value="3"> >= 3 </option>
                                                <option value="3.5"> >= 3.5 </option>
                                                <option value="4"> >= 4 </option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Odd To</label>
                                            <select class="form-control select-maxOdd">
                                                <option value=""> -- all -- </option>
                                                <option value="1.5"> <= 1.5 </option>
                                                <option value="2"> <= 2 </option>
                                                <option value="2.5"> <= 2.5 </option>
                                                <option value="3"> <= 3 </option>
                                                <option value="3.5"> <= 3.5 </option>
                                                <option value="4"> <= 4 </option>
                                                <option value="4.5"> <= 4.5 </option>
                                                <option value="5"> <= 5 </option>
                                                <option value="5.5"> <= 5.5 </option>
                                                <option value="6"> <= 6 </option>
                                                <option value="10"> <= 10 </option>
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <label>&nbsp</label>
                                            <button class="btn btn-info btn-fill pull-right modal-get-event">Go</button>
                                        </div>
                                        <div class="col-md-1">
                                            <label>&nbsp</label>
                                            <button class="btn btn-success btn-fill pull-right refresh-event-info">
                                                <i class="pe-7s-refresh"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Salary</th>
                                        <th>Country</th>
                                        <th>City</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Dakota Rice</td>
                                            <td>$36,738</td>
                                            <td>Niger</td>
                                            <td>Oud-Turnhout</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Minerva Hooper</td>
                                            <td>$23,789</td>
                                            <td>Curaçao</td>
                                            <td>Sinaai-Waas</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<!-- modal import available events -->
<div id="modal-available-events" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Import events</h4>
            </div>
            <div class="modal-body"></div>
            <script class="template-modal-body" type="text/template7">
                <input type="hidden" value="{{table}}"/>
                <div class="content table-responsive table-full-width">
                    <table class="table table-hover table-striped">
                        <thead>
                            <th>Use</th>
                            <th>Id</th>
                            <th>Country</th>
                            <th>League</th>
                            <th>Home Team</th>
                            <th>Away Team</th>
                            <th>Odd</th>
                            <th>Prediction</th>
                            <th>Result</th>
                            <th>Status</th>
                            <th>Event Date</th>
                        </thead>
                        <tbody>
                        {{#each events}}
                            <tr class="event">
                                <td><input class="use" type="checkbox" data-id="{{id}}"></td>
                                <td>{{id}}</td>
                                <td>{{country}}</td>
                                <td>{{league}}</td>
                                <td>{{homeTeam}}</td>
                                <td>{{awayTeam}}</td>
                                <td>{{odd}}</td>
                                <td>{{predictionId}}</td>
                                <td>{{result}}</td>
                                <td>{{statusId}}</td>
                                <td>{{eventDate}}</td>
                            </tr>
                        {{/each}}
                        </tbody>
                    </table>
                </div>
            </script>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>System Date</label>
                            <input class="form-control system-date" type="text" value="2017-07-26 00:00:00">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary import">Import Selected Events</button>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
