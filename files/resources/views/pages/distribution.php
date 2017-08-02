<div class="content page-container distribution hidden">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div id="container-distributed-events" class="card">
                    <div class="header">
                        <h4>
                            <button class="btn btn-danger btn-fill pull-right">Delete</button>
                            <button class="btn btn-info btn-fill pull-right">Publish</button>
                        </h4>
                    </div>

                    <!-- content of table distributed events -->
                    <div class="table-content"></div>
                    <script class="template-table-content" type="text/template7">
                        {{#each distribution}}
                        <div class="header">
                            <h4 class="title">Tips Distribution: {{systemDate}}</h4>
                        </div>
                            {{#each sites}}
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="header">
                                            <h3><input class="select-group-site" type="checkbox"> {{name}}</h3>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        {{#each packages}}
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="header">
                                                    <h5>{{name}} {{eventsNumber}}/{{tipsPerDay}}</h5>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="content table-responsive table-full-width">
                                                    <table class="table table-hover table-striped">
                                                    <!--
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
                                                            <th>System Date</th>
                                                        </thead>
                                                        -->
                                                        <tbody class="table-body">
                                                            {{#each events}}
                                                            <tr data-id="{{id}}">
                                                                <td><input class="use" type="checkbox" data-id="{{id}}"/></td>
                                                                <td>{{id}}</td>
                                                                <td>{{country}}</td>
                                                                <td>{{league}}</td>
                                                                <td>{{homeTeam}}</td>
                                                                <td>{{awayTeam}}</td>
                                                                <td>{{odd}}</td>
                                                                <td>{{predictionName}}</td>
                                                                <td>{{result}}</td>
                                                                <td>{{statusId}}</td>
                                                                <td>{{eventDate}}</td>
                                                                <td>{{systemDate}}</td>
                                                            </tr>
                                                            {{else}}
                                                            <tr>
                                                                <td class="text-center">No events yet.</td>
                                                            </tr>
                                                            {{/each}}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        {{/each}}
                                    </div>
                                </div>
                            {{/each}}
                        {{/each}}
                    </script>

                </div>
            </div>
        </div>
    </div>
</div>


