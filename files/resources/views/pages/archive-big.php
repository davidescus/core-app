<div class="content page-container archive-big hidden">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div id="container-archive-big" class="card">
                    <div class="header">
                        <h4>Big Archive</h4>
                    </div>
                    <div class="content table-responsive table-full-width">
                        <table class="table table-hover table-striped">

                            <!-- content of table distributed events -->
                            <div class="table-content"></div>
                            <script class="template-table-content" type="text/template7">
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
                                <tbody class="table-body">
                                    {{#each events}}
                                    <tr>
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
                            </script>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


