<!doctype html>
<html>
<head>
  <title>Gmail API demo</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>  
           <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">  
            <script src="https://rawgit.com/moment/moment/2.2.1/min/moment.min.js"></script>
<link rel="stylesheet" href="css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/dataTables.bootstrap.css"/>
  <style>
    iframe {
      width: 100%;
      border: 0;
      min-height: 80%;
      height: 600px;
      display: flex;
    }
  </style>
</head>
  <body>
    <div class="container">
      <h1>Gmail API demo</h1>
          <section class="pull-left text-center">
                                <p class="text-left">Status: <span id="connection_status"></span><br/></p>
                                <p class="text-left" id="p_connection_profile">Account: <span id="connection_profile" class="text-info"></span><br/></p>
                                <p class="text-left">
                                    <button id="authorize-button" class="btn btn-success hidden" style="width:150px; margin-top:5px">Connect</button>
                                    <button id="signout-button" class="btn btn-danger hidden"  style="width:150px; margin-top:5px">Disconnect</button>
                                </p>
                                <br/><br/>

                               <div class="form-group">
                                   
                                    <select class="form-control"  id="OperationType" onchange="check();">
                                     
                                      <option value="Select">Select</option>
                                       <option value="Filter">Filter</option>
                                    
                                    </select>
                                  </div>
                            </section>
                       
                        
                      
                        <div id=OperationNos style="display: none;">
                          <div class="col-sm-12" >
                           <div class="col-sm-3" > 
                    
               <input type="text" id="myInputSender" onkeyup="myFunction()" class="form-control hidden" placeholder="Sender">
              
                </div>
                       <div class="col-sm-3" > 
                    
               <input type="text" id="myInputSubject" onkeyup="myFunctionSubject()" class="form-control hidden" placeholder="Subject">
               
                </div>
                  <div class="col-sm-3" > 
                   
               <input type="text" id="myInputContent" onkeyup="myFunctionContent()" class="form-control hidden" placeholder="Content">
               
                </div>
                </div>
              
                <div class="hidden" id="fil">
                <div class='col-lg-12' style="margin-top: 15px; margin-bottom: 15px;">
                    <label><input type="checkbox" " name="advfilter" checked=""  onclick="advfilter()">Show advanced filters</label>
                            <div id="advfilter">
                                     <div class="col-md-3">  
                     
                  <input id="searchFrom" class="searchInput" type="text" placeholder="From"/>

                </div>  
                <div class="col-md-3">  
                   <input id="searchTo" class="searchInput" type="text" placeholder="To" >  
                </div>  

              

                </div>
                </div>
              </div>
            </div>
                <div class='col-lg-12' style="margin-top: 15px; margin-bottom: 15px;">
                      <div id="divRetrieve">
                            <div class='col-lg-2'>
                                <input type="text" class="number form-control" name="noOfEmails" id="noOfEmails" placeholder="No. of Emails">
                            </div>
                            <div class='col-lg-1'>
                                <button id="retrieve-button" class="btn btn-success" onclick="displayInbox()">Retrieve</button>
                            </div>
                        </div>
                    </div>
  <div id="hasExistingEmails" class="col-lg-12" style="display:none">
                            <br/><input type="hidden" id="emailcnt" value="0">
                            <div class="col-lg-12 alert alert-info">
                                <span id="noOfExistingEmails"></span>
                            </div>
                        </div>
              <section id="section_records">
            <!--     <div class="col-lg-18">
                <div class="col-lg-12" style="width: 20%; float: right;display:block;">
                              <input style="" type="text"  id="myInput"  class="form-control " placeholder="Search"><br>
                               </div>
                               </div> -->
                                  <table id="tbl_records" class="table table-striped table-bordered table-inbox">
                        <thead>
                            <tr>
                                <!--<td class='tbth text-center'><input type="checkbox" id="chkAll" align="center"></td>-->
                                <td class='text-center' nowrap>From</td>
                                <td class='text-center' nowrap>Subject</td>
                                <td class='text-center' nowrap>Email Content</td>
                                <td class='text-center' nowrap>Attachment(s)</td>
                                <td class='text-center' nowrap>Date Received</td>
                                <td class='text-center' nowrap>Action</td>
                                <td hidden></td>
                            </tr>
                               <tbody id="tbl_tbody"></tbody>
                        </thead>
                   
                    </table>
 
                </section>
    </div>

            
      
  
        </div>


  <!--   <script type="text/javascript">
        
           $(document).ready(function(){  
           $.datepicker.setDefaults({  
                dateFormat: 'mm/dd/yy '}) 
           });  

       $(function(){  
                $("#searchFrom").datepicker();  
                $("#searchTo").datepicker();  
           });  
    </script> -->

                   
                      

    <script type="text/javascript">

function advfilter() {
  var x = document.getElementById("advfilter");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}
$(".searchInput").on("input", function() {
  var from = stringToDate($("#searchFrom").val());
  var to = stringToDate($("#searchTo").val());

  $("table tr").each(function() {
    var row = $(this);
    var date = stringToDate(row.find("td").eq(5).text());
    
    //show all rows by default
    var show = true;

    //if from date is valid and row date is less than from date, hide the row
    if (from && date < from)
      show = false;
    
    //if to date is valid and row date is greater than to date, hide the row
    if (to && date > to)
      show = false;

    if (show)
      row.show();
    else
      row.hide();
  });
});

//parse entered date. return NaN if invalid
function stringToDate(s) {
  var ret = NaN;
  var parts = s.split("/");
  date = new Date(parts[2], parts[0], parts[1]);
  if (!isNaN(date.getTime())) {
    ret = date;
  }
  return ret;
}


function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInputSender");
  filter = input.value.toUpperCase();
  table = document.getElementById("tbl_records");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}

function myFunctionSubject() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInputSubject");
  filter = input.value.toUpperCase();
  table = document.getElementById("tbl_records");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}

function myFunctionContent() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInputContent");
  filter = input.value.toUpperCase();
  table = document.getElementById("tbl_records");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {

    td = tr[i].getElementsByTagName("td")[2];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}
      var clientId = '399013516612-52n7dthtsdqomgd1r642faqa85dgo5hn.apps.googleusercontent.com';
      var apiKey = '';
      var scopes = 'https://www.googleapis.com/auth/gmail.readonly';

      function handleClientLoad() {
        gapi.client.setApiKey(apiKey);
        window.setTimeout(checkAuth, 1);
      }

      function checkAuth() {
        gapi.auth.authorize({
          client_id: clientId,
          scope: scopes,
          immediate: true
        }, handleAuthResult);
      }

      function handleAuthClick() {
        gapi.auth.authorize({
          client_id: clientId,
          scope: scopes,
          immediate: false
        }, handleAuthResult);
        return false;
      }
        function handleSignoutClick() {
        
        var logOutUrl = "https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue";

        document.location.href = logOutUrl;
    }
      function handleAuthResult(authResult) {
        if(authResult && !authResult.error) {
            loadGmailApi1();
            $('#authorize-button').remove();
            $("#divRetrieve").show();
            $("#pNotConnectedMsg").hide();
            $('#myInputSender').removeClass("hidden");
            $('#myInputContent').removeClass("hidden");
            $('#myInputSubject').removeClass("hidden");
             $('#advfilter').removeClass("hidden");
             $('#fil').removeClass("hidden");
            $('#signout-button').removeClass("hidden");
             $('.table-inbox').removeClass("hidden");
            $('#signout-button').on('click', function(){
                handleSignoutClick();
            });
        } else {
            $("#p_connection_profile").hide();
            $("#pNotConnectedMsg").show();
            $("#connection_status").text('Not Connected');
            $("#connection_status").css('color', '#FF0000');
            $('#authorize-button').removeClass("hidden");
            $("#divRetrieve").hide();
            $('#authorize-button').on('click', function(){
                handleAuthClick();
            });
        }
        
  
    }



    function getProfile() {
        var profile = '';
        var request = gapi.client.gmail.users.getProfile({
            'userId': 'me'
        });
        
        request.execute(function(response) {
            profile = response.emailAddress;
            $("#connection_status").text('Connected');
            $("#connection_status").css('color', '#4cae4c');
            $("#p_connection_profile").show();
            $("#connection_profile").text(profile);
            
        });
    }


      function loadGmailApi1() {
        gapi.client.load('gmail', 'v1', getProfile);
        gapi.client.load('gmail', 'v1', displayInbox);
      }


         $('.number').bind('keypress', function(event) {
        var regex = new RegExp("^[0-9\b]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        var charCode = event.which;
        if (charCode === 0) {
            return;
        } else if (!regex.test(key)) {
            event.preventDefault();
            return false;
        }
    });
    


function displayInbox() {
        var noOfEmails = $("#noOfEmails").val();
            $("#emailcnt").val('0');
            window.g_existing_mid = [];
            
        if(noOfEmails == "" || noOfEmails == undefined || noOfEmails <= 0){
            alert("Plese enter the number of emails you want to retrieve from Gmail.");
            $("#noOfEmails").focus();
        } else if(noOfEmails > 100) {
            alert("Request limit reached. (Maximum emails allowed: 100)");
            $("#noOfEmails").val(100);
        } else {
            
            var profile_id = btoa($("#connection_profile").text()),
                client_id = '399013516612-52n7dthtsdqomgd1r642faqa85dgo5hn.apps.googleusercontent.com',
                d = 0;
                
            $.get( "test.php?profile_id=" + profile_id + "&client_id=" + client_id, function( data ) {
             /*   var result = JSON.parse(data);
                
                for(c=0; c<result[0].data.length; c++){
                    window.g_existing_mid.push(result[0].data[c].message_id);
                }
                
                if(result[0].data.length <= 0){
                    $('#btnExport').addClass("hidden");
                }*/
                
                var request = gapi.client.gmail.users.messages.list({
                    'userId': 'me',
                    'labelIds': 'INBOX',
                    'maxResults': noOfEmails
                });
                
                request.execute(function(response) {
                    $("#noOfEmails").val(response.messages.length);
                    $('#section_records').hide();
                   
                    $("#tbl_records").dataTable().fnDestroy();
                    $("#tbl_records tbody").html('');
                    $("#noOfExistingEmails").text('');
                    $("#hasExistingEmails").hide();
                    
                    $.each(response.messages, function() {
                        var messageRequest = gapi.client.gmail.users.messages.get({
                            'userId': 'me',
                            'id': this.id
                        });
                        messageRequest.execute(appendMessageRow);
                    });
                });
            });
        }
    }




  function appendMessageRow(message) {
        
        var from = getHeader(message.payload.headers, 'From'),
            subject = getHeader(message.payload.headers, 'Subject').replace(/\"/g, '&quot;'),
            date = getHeader(message.payload.headers, 'Date'),
            to = getHeader(message.payload.headers, 'To'),
            snippet = message.snippet.substr(0,50),
            rows = '', modal = '', attachments = [];
            $exstClass = '';
            $exstTitle = '';
            $exstBtnSaveDisplay = '';
            
            if($.inArray(message.id, window.g_existing_mid) !== -1){
                $exstClass = 'alert alert-danger';
                $exstTitle = 'This message has been saved to the Notes Tool already';
                $exstBtnSaveDisplay = 'hidden';
            }
            

            rows += '<tr>';
            rows += '<td>'+ from +'</td>';
            //rows += '<td>'+ to +'</td>';
            rows += '<td>'+ subject +'</td>';
            rows += '<td>';
        
                if(snippet != ''){
                    rows += snippet + '... <span class="readmore" id="message-link-' + message.id + '">read more</span>';
                }
        
            rows += '</td>';
            rows += '<td>';
            
            if(message.payload.filename != ""){
                attachmentfilename = message.payload.filename;
            } else {
                if(message.payload.parts != undefined){
                    for(a = 0; a < message.payload.parts.length; a++){
                        if(message.payload.parts[a].filename != '' && message.payload.parts[a].filename != undefined){
                            var params = {
                                atch_id: message.payload.parts[a].body.attachmentId,
                                atch_filename: message.payload.parts[a].filename,
                                atch_mimeType: message.payload.parts[a].mimeType,
                                atch_size: message.payload.parts[a].body.size
                            }
                            
                            attachments.push(params);
                            rows += message.payload.parts[a].filename + '; ';
                        }
                    }
                }
            }

            rows += '</td>';
            rows += '<td class="text-center" data-order="'+ moment.unix(message.internalDate/1000).format("YYYY-MM-DD-HH:mm") +'" >'+ moment.unix(message.internalDate/1000).format("DD MMM YYYY HH:mm A") +'</td>';
             rows += '<td HIDDEN class="text-center" data-order="'+ moment.unix(message.internalDate/1000).format("YYYY-MM-DD-HH:mm") +'" >'+ moment.unix(message.internalDate/1000).format("MM/DD/YYYY") +'</td>';
            rows += '<td class="text-center" nowrap>';
            rows += '<button type="button" class="btn btn-xs btn-success btn-row-action '+$exstBtnSaveDisplay+'" id="save-message-link-' + message.id + '">Save</button>';
            rows += '<button type="button" class="btn btn-xs btn-primary btn-row-action" id="reply-message-link-' + message.id + '">Reply</button>';
            rows += '</td>';
            rows += '</tr>';

            $('.table-inbox tbody').append(rows);

        var emctr = parseInt($("#emailcnt").val()),
            reqmsg = parseInt($("#noOfEmails").val());
            emctr = emctr + 1;
            $("#emailcnt").val(emctr);
        
            if(reqmsg == emctr){
                console.log('generate now');
                setTimeout(function(){
                  var dTable = $('#tbl_records');
                   dTable.dataTable({
                        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                        "aoColumnDefs" : [{'bSortable' : false, 'aTargets' : [-1]}],
                        "aaSorting": [4, 'desc']
                    });
                    
                    $('#section_records').show();
                  
                    $('.table-inbox').removeClass("hidden");
                  
                    //$('#assignToNotes').removeClass("hidden");
                    console.log('table is generated');
                }, 1000);
       }


    }

    
   

    function getHeader(headers, index) {
        var header = '';
        $.each(headers, function(){
            if(this.name.toLowerCase() === index.toLowerCase()){
                header = this.value;
            }
        });
        return header;
    }

    function getBody(message) {
        var encodedBody = '';
        if(message.parts === undefined){
            encodedBody = message.body.data;
        } else {
            encodedBody = getHTMLPart(message.parts);
        }
        
        encodedBody = encodedBody.replace(/-/g, '+').replace(/_/g, '/').replace(/\s/g, '');
        return decodeURIComponent(escape(window.atob(encodedBody)));
    }

    function getHTMLPart(arr) {
        
        for(var x = 0; x <= arr.length; x++){
            //console.log(arr[x]);
            
            if(arr[x]!==undefined){
                if(arr[x].parts !== undefined){
                    return getHTMLPart(arr[x].parts);
                } else {
                    if(arr[x].mimeType === 'text/html'){
                        return arr[x].body.data;
                    }
                }    
            } else {
                return '';
            }
            
        }
        return '';
    }
    

    </script>


    <script type="text/javascript">
  function check() {
    var dropdown = document.getElementById("OperationType");
    var current_value = dropdown.options[dropdown.selectedIndex].value;

    if (current_value == "Filter") {
        document.getElementById("OperationNos").style.display = "block";
    }

    else {
        document.getElementById("OperationNos").style.display = "none";
    }
}
</script>
<!-- <script type="text/javascript">
    function filterTable(event) {
    var filter = event.target.value.toUpperCase();
    var rows = document.querySelector("#tbl_records tbody").rows;
    
    for (var i = 0; i < rows.length; i++) {
        var firstCol = rows[i].cells[0].textContent.toUpperCase();
        var secondCol = rows[i].cells[1].textContent.toUpperCase();
        var thirdCol = rows[i].cells[2].textContent.toUpperCase();
        var forthCol = rows[i].cells[3].textContent.toUpperCase();
        var fifthCol = rows[i].cells[4].textContent.toUpperCase();
        if (firstCol.indexOf(filter) > -1 || secondCol.indexOf(filter) > -1 || thirdCol.indexOf(filter) > -1 || forthCol.indexOf(filter) > -1  || fifthCol.indexOf(filter) > -1 ){
            rows[i].style.display = ""; 
        } else {
            rows[i].style.display = "none";
        }      
    }
}

document.querySelector('#myInput').addEventListener('keyup', filterTable, false);
</script> -->
       

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#searchFrom" ).datepicker();
  } );
   $( function() {
    $( "#searchTo" ).datepicker();
  } );
  </script>
<script src="js/jquery.dataTables.js"></script>
<script src="js/dataTables.bootstrap.js"></script>
    <script src="https://apis.google.com/js/client.js?onload=handleClientLoad"></script>
 
  </body>
</html>
