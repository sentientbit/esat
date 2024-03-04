<?php

echo '   
<div class="card text-black bg-white mb-3" style="max-width: 100%; border: none">
    <div class="card-header bg-white">
        <h1 class="text-center font-weight-bold">Security level</h1>
    </div>
    <div class="card-body m-0 p-0">
        <div class="container">
            <div class="row mt-4 mb-4" style="display: flex; align-items: center;">
                <div class="col-sm-6">
                    <div class="progress yellow">
                        <span class="progress-left">
                            <span class="progress-bar"></span>
                        </span>
                        <span class="progress-right">
                            <span class="progress-bar"></span>
                        </span>
                        <div class="inner-circle"></div>
                        <div class="progress-value"><span>75</span>%</div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row" class="text-success">Passed</th>
                                <td>33</td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-danger">Failed</th>
                                <td>6</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <p class="lead" style="display: flex; column-gap: 20px;">
            <a class="container btn btn-primary btn-lg" href="#" role="button">Security check</a>
            <a class="container btn btn-primary btn-lg" href="#" role="button">Customize</a>
        </p>
    </div>
</div>
<hr class="hr" style="height: 1px; border:0; border-top: 1px solid #000;" />
<ul style="margin: 0;padding: 0;list-style: none;">
        <li style="margin: 10px 0;padding: 10px;line-height: 30px;border: 1px solid #f3ebd0; border-left: 2px solid #d63638;"> Change the wp-content, wp-includes and other common paths with <a href="https://sentientbit.com/wp-admin/admin.php?page=hmwp_permalinks#tab=core"> Hide My WP &gt; Change Paths</a></li>
        <li style="margin: 10px 0;padding: 10px;line-height: 30px;border: 1px solid #f3ebd0; border-left: 2px solid #d63638;"> Switch on <a href="https://sentientbit.com/wp-admin/admin.php?page=hmwp_permalinks#tab=core"> Hide My WP &gt; Change Paths &gt;  Hide WordPress Common Paths</a></li>
        <li style="margin: 10px 0;padding: 10px;line-height: 30px;border: 1px solid #f3ebd0; border-left: 2px solid #d63638;"> Switch on <a href="https://sentientbit.com/wp-admin/admin.php?page=hmwp_permalinks#tab=api"> Hide My WP &gt; Change Paths &gt; Hide RSD Endpoint</a></li>
</ul>
';
