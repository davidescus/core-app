        <div class="content page-container association hidden">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">
                        <div id="table-association-run" class="card table-association" data-table="run">
                            <div class="header">
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
                            <div class="table-association-content"></div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div id="table-association-ruv" class="card table-association" data-table="ruv">
                            <div class="header">
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
                            <div class="table-association-content"></div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div id="table-association-nun" class="card table-association" data-table="nun">
                            <div class="header">
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
                            <div class="table-association-content"></div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div id="table-association-nuv" class="card table-association" data-table="nuv">
                            <div class="header">
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
                            <div class="table-association-content"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<!-- content of table association -->
<script id="template-table-association-content" type="text/template7">
    <div class="content table-responsive table-full-width">
        <table class="table table-hover table-striped">
            <thead>
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
                <th>Actions</th>
            </thead>
            <tbody class="table-body"></tbody>
                {{#each associations}}
                <tr data-id="{{id}}">
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
                    <td>
                        <button type="button" class="btn btn-info btn-fill modal-available-packages">Assoc</button>
                        <button type="button" class="btn btn-danger btn-fill delete-event">Del</button>
                    </td>
                </tr>
                {{/each}}
        </table>
    </div>
</script>

<!-- modal import available events -->
<div id="modal-available-events" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"></div>
        <script class="template-modal-content" type="text/template7">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">Import events: {{table}}</h4>
        </div>
        <div class="modal-body">
            <input class="table-identifier" type="hidden" value="{{table}}"/>
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
        </div>
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
        </script>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- modal associate events -->
<div id="modal-associate-events" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"></div>
        <script class="template-modal-content" type="text/template7">
        <input class="event-id" type="hidden" value="{{event.id}}">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="row">
                <div class="col-sm-2">
                    <h5>Associate: </h5>
                    <h4>{{table}}</h4>
                </div>
                <div class="col-sm-10">
                    <h5 class="modal-title">
                        {{event.country}} :
                        {{event.league}}
                        {{event.homeTeam}} -
                        {{event.awayTeam}}
                        {{event.predictionId}}
                        {{event.eventDate}}
                    </h5>
                </div>
            </div>
        </div>
        <div class="modal-body">
            {{#each sites}}
            <div class="row">
                <div class="col-sm-3">
                    {{siteName}}
                </div>
                <div class="col-sm-9">
                    {{#each packages}}
                    <div>
                        <input class="use" type="checkbox" value="{{id}}">
                        {{name}}
                    </div>
                    {{/each}}
                </div>
            </div>
            </br>
            {{/each}}
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary associate-event">Associate event with packages</button>
        </div>
        </script>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
