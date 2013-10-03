<div class="grid_12">
    <div class="box with-table" id="customReportContent">
        <div class="header">
            <h2>Report Result</h2>
        </div>
        <div class="content">
            <div class="tabletools">
                <div class="right">
                    <!--<a href="#" onclick="exportCsv(); return false;">CSV</a>-->
                </div>
            </div>
            <div style="overflow-x:scroll;clear:both;">
                <table class="styled" id="result_table" cellpadding="0" cellspacing="0">
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(function(){
        $("#customReportContent").hide();
        $("#generate_custom_report").click(function(e) {
            e.preventDefault();
            $.post('/ajax/reports/customReport',$('#user_prefs').serialize(true),
                function(data) {
                    alert(data);
                    $("#result_table").html(data);
                    $("#customReportContent").show();
                }
            );
            return false;
        });
    });

    function exportCsv() {
        iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        document.body.appendChild(iframe);
        iframe.src = '/overview/exportBreakdown?iSortCol_0=0&sSortDir_0=asc';
    }

    function $getChecked(object) {return document.getElementById(object).checked}

    function setAllData() {
        var set = $getChecked("allData");
        if (set) {
            document.getElementById('clickData').checked = true;
            <?php
               $select = "click.click_id"
             ?>
            document.getElementById('campData').checked = true;
            document.getElementById('deviceData').checked = true;
            document.getElementById('carrierData').checked = true;
            document.getElementById('tokenData').checked = true;
        } else {
            document.getElementById('clickData').checked = false;
            document.getElementById('campData').checked = false;
            document.getElementById('deviceData').checked = false;
            document.getElementById('carrierData').checked = false;
            document.getElementById('tokenData').checked = false;
        }
        setReportData(0, 1);
    }

    function setReportData(i, setAll) {
        var set;
        if (i === 1 || setAll) {
            set = $getChecked("clickData");
            if (set) {
                document.getElementById('sid').checked = true;
                document.getElementById('ts').checked = true;
                document.getElementById('dt').checked = true;
                document.getElementById('ip').checked = true;
                document.getElementById('ref').checked = true;
                document.getElementById('ua').checked = true;
            } else {
                document.getElementById('sid').checked = false;
                document.getElementById('ts').checked = false;
                document.getElementById('dt').checked = false;
                document.getElementById('ip').checked = false;
                document.getElementById('ref').checked = false;
                document.getElementById('ua').checked = false;
            }
        }
        if (i === 2 || setAll) {
            set = $getChecked("campData");
            if (set) {
                document.getElementById('cid').checked = true;
                document.getElementById('cn').checked = true;
                document.getElementById('cpc').checked = true;
                document.getElementById('on').checked = true;
                document.getElementById('ld').checked = true;
                document.getElementById('po').checked = true;
            } else {
                document.getElementById('cid').checked = false;
                document.getElementById('cn').checked = false;
                document.getElementById('cpc').checked = false;
                document.getElementById('on').checked = false;
                document.getElementById('ld').checked = false;
                document.getElementById('po').checked = false;
            }
        }
        if (i === 3 || setAll) {
            set = $getChecked("deviceData");
            if (set) {
                document.getElementById('name').checked = true;
                document.getElementById('mdl').checked = true;
                document.getElementById('type').checked = true;
                document.getElementById('os').checked = true;
            } else {
                document.getElementById('name').checked = false;
                document.getElementById('mdl').checked = false;
                document.getElementById('type').checked = false;
                document.getElementById('os').checked = false;
            }
        }
        if (i === 4 || setAll) {
            set = $getChecked("carrierData");
            if (set) {
                document.getElementById('carrier').checked = true;
                document.getElementById('isp').checked = true;
                document.getElementById('code').checked = true;
                document.getElementById('country').checked = true;
            } else {
                document.getElementById('carrier').checked = false;
                document.getElementById('isp').checked = false;
                document.getElementById('code').checked = false;
                document.getElementById('country').checked = false;
            }
        }
        if (i === 5 || setAll) {
            set = $getChecked("tokenData");
            if (set) {
                if (document.getElementById('c1').value === "1") {
                    document.getElementById('c1').checked = true;
                }
                if (document.getElementById('c2').value === "1") {
                    document.getElementById('c2').checked = true;
                }
                if (document.getElementById('c3').value === "1") {
                    document.getElementById('c3').checked = true;
                }
                if (document.getElementById('c4').value === "1") {
                    document.getElementById('c4').checked = true;
                }
                if (document.getElementById('c5').value === "1") {
                    document.getElementById('c5').checked = true;
                }
                if (document.getElementById('c6').value === "1") {
                    document.getElementById('c6').checked = true;
                }
                if (document.getElementById('c7').value === "1") {
                    document.getElementById('c7').checked = true;
                }
                if (document.getElementById('c8').value === "1") {
                    document.getElementById('c8').checked = true;
                }
                if (document.getElementById('c9').value === "1") {
                    document.getElementById('c9').checked = true;
                }
                if (document.getElementById('c10').value === "1") {
                    document.getElementById('c10').checked = true;
                }
            } else {
                document.getElementById('c1').checked = false;
                document.getElementById('c2').checked = false;
                document.getElementById('c3').checked = false;
                document.getElementById('c4').checked = false;
                document.getElementById('c5').checked = false;
                document.getElementById('c6').checked = false;
                document.getElementById('c7').checked = false;
                document.getElementById('c8').checked = false;
                document.getElementById('c9').checked = false;
                document.getElementById('c10').checked = false;
            }
        }
    }
</script>