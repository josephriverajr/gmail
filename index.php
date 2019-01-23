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

                            </section>
                       
                        
                        <div id="hasExistingEmails" class="col-lg-12" style="display:none">
                            <br/><input type="hidden" id="emailcnt" value="0">
                            <div class="col-lg-12 alert alert-info">
                                <span id="noOfExistingEmails"></span>
                            </div>
                        </div>
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

              <section id="section_records">
                                  <table id="tbl_records" class="table table-striped table-bordered table-inbox hidden">
                        <thead>
                            <tr>
                                <!--<td class='tbth text-center'><input type="checkbox" id="chkAll" align="center"></td>-->
                                <td class='text-center' nowrap>From</td>
                                <td class='text-center' nowrap>Subject</td>
                                <td class='text-center' nowrap>Email Content</td>
                                <td class='text-center' nowrap>Attachment(s)</td>
                                <td class='text-center' nowrap>Date Received</td>
                                <td class='text-center' nowrap>Action</td>
                            </tr>
                        </thead>
                        <tbody id="tbl_tbody"></tbody>
                    </table>
                    
                    
                </section>
    </div>

            
      
  
        </div>


<!--     <script type="text/javascript">
        
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
        document.getElementById("tbl_records").removeChild(document.getElementById("tbl_tbody"));
        $('.table-inbox').append('<tbody id="tbl_tbody"></tbody>');
        var noOfEmails = document.getElementById("noOfEmails").value || 0;
        var request = gapi.client.gmail.users.messages.list({
          'userId': 'me',
          'labelIds': 'INBOX',
          'maxResults': noOfEmails 
        });

        request.execute(function(response) {
          $.each(response.messages, function() {
            var messageRequest = gapi.client.gmail.users.messages.get({
              'userId': 'me',
              'id': this.id
            });

            messageRequest.execute(appendMessageRow);
          });
        });
      }




      function appendMessageRow(message) {
        var  s = getHeader(message.payload.headers, 'Date');

         function reformatDate(s) {


  
  function z(n){return ('0' + n).slice(-2)}
  var months = [,'jan','feb','mar','apr','may','jun',
                 'jul','aug','sep','oct','nov','dec'];
  var b = s.split(/\W+/);
  return b[3] + '/' +
    z(months.indexOf(b[1].substr(0,3).toLowerCase())) + '/' +
    z(b[2]);
}



console.log(reformatDate(s));

        $('.table-inbox tbody').append(
          '<tr>\
            <td>'+getHeader(message.payload.headers, 'From')+'</td>\
            <td>\
              <a href="#message-modal-' + message.id +
                '" data-toggle="modal" id="message-link-' + message.id+'">' +
                getHeader(message.payload.headers, 'Subject') +
              '</a>\
            </td>\
            <td>'+message.snippet.substring(0,50)+'...<a href="#">read more</a></td>\
            <td>'+message.attachments+'</td>\
            <td>' /*moment.unix(message.internalDate/1000).format("YYYY-MM-DD-HH:mm") +' >'*/+ moment.unix(message.internalDate/1000).format("DD MMM YYYY HH:mm A") +'</td>\
            <td hidden>'+ moment.unix(message.internalDate/1000).format("MM/DD/YYYY") +'</td>\
            <td>'+"<button class='btn btn-xs btn-success' btn-row-action>Save</button>"+"<button class='btn btn-xs btn-info' btn-row-action>Reply</button>"+'</td>\
          </tr>'
        );

        $('body').append(
          '<div class="modal fade" id="message-modal-' + message.id +
              '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">\
            <div class="modal-dialog modal-lg">\
              <div class="modal-content">\
                <div class="modal-header">\
                  <button type="button"\
                          class="close"\
                          data-dismiss="modal"\
                          aria-label="Close">\
                    <span aria-hidden="true">&times;</span></button>\
                  <h4 class="modal-title" id="myModalLabel">' +
                    getHeader(message.payload.headers, 'Subject') +
                  '</h4>\
                </div>\
                <div class="modal-body">\
                  <iframe id="message-iframe-'+message.id+'" srcdoc="<p>Loading...</p>">\
                  </iframe>\
                </div>\
              </div>\
            </div>\
          </div>'
        );

        $('#message-link-'+message.id).on('click', function(){
          var ifrm = $('#message-iframe-'+message.id)[0].contentWindow.document;
          $('body', ifrm).html(getBody(message.payload));
        });
      }

      function getHeader(headers, index) {
        var header = '';

        $.each(headers, function(){
          if(this.name === index){
            header = this.value;
          }
        });
        return header;
      }

      function getBody(message) {
        var encodedBody = '';
        if(typeof message.parts === 'undefined')
        {
          encodedBody = message.body.data;
        }
        else
        {
          encodedBody = getHTMLPart(message.parts);
        }
        encodedBody = encodedBody.replace(/-/g, '+').replace(/_/g, '/').replace(/\s/g, '');
        return decodeURIComponent(escape(window.atob(encodedBody)));
      }

      function getHTMLPart(arr) {
        for(var x = 0; x <= arr.length; x++)
        {
          if(typeof arr[x].parts === 'undefined')
          {
            if(arr[x].mimeType === 'text/html')
            {
              return arr[x].body.data;
            }
          }
          else
          {
            return getHTMLPart(arr[x].parts);
          }
        }
        return '';
      }


    </script>
    <script src="https://apis.google.com/js/client.js?onload=handleClientLoad"></script>
  </body>
</html>
