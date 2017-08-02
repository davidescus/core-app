        <div class="content page-container distribution hidden">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div id="container-distributed-events" class="card">
                            <div class="header">
                                <h4 class="title">Tips Distribution</h4>
                            </div>

                            <!-- content of table distributed events -->
                            <div class="table-content"></div>
                            <script class="template-table-content" type="text/template7">
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
                                            <th>System Date</th>
                                            <th>Actions</th>
                                        </thead>
                                        <tbody class="table-body"></tbody>
                                            {{#each distribution}}
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
                                                <td>{{systemDate}}</td>
                                                <td>
                                                    <button type="button" class="btn btn-success btn-fill publiish">Pub</button>
                                                </td>
                                            </tr>
                                            {{/each}}
                                    </table>
                                </div>
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </div>


