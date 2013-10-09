<div class="grid_12">
    <div class="box with-table" id="customReportContent">
        <div class="header">
            <h2>Report Result</h2>
        </div>
        <div class="content">
            <div class="tabletools">
                <div class="right">
                    <a href="#" onclick="exportCsv(); return false;">CSV</a>
                </div>
            </div>
            <div id="errorData" style="display: none;">
                <p>No options have been selected. To create a report, select from the options above and click 'Create Report' below.</p>
            </div>
            <table class="dataTable" id="result_table" cellpadding="0" cellspacing="0">
            </table>
        </div>
    </div>
</div>
<script src="/theme/js/scripts/customReport.js"></script>