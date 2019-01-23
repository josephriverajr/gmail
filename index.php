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
            
            rows += '<tr class="'+ $exstClass +'" title="'+ $exstTitle +'">';
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
             rows += '<td hidden class="text-center" data-order="'+ moment.unix(message.internalDate/1000).format("YYYY-MM-DD-HH:mm") +'" >'+ moment.unix(message.internalDate/1000).format("M/D/YY") +'</td>';
            rows += '<td class="text-center" nowrap>';
            rows += '<button type="button" class="btn btn-xs btn-success btn-row-action '+$exstBtnSaveDisplay+'" id="save-message-link-' + message.id + '">Save</button>';
            rows += '<button type="button" class="btn btn-xs btn-primary btn-row-action" id="reply-message-link-' + message.id + '">Reply</button>';
            rows += '</td>';
            rows += '</tr>';

            $('.table-inbox tbody').append(rows);
        
        var reply_to = (getHeader(message.payload.headers, 'Reply-to') !== '' ? getHeader(message.payload.headers, 'Reply-to') : getHeader(message.payload.headers, 'From')).replace(/\"/g, '&quot;');
        var reply_subject = getHeader(message.payload.headers, 'Subject').replace(/\"/g, '&quot;');
        
        if (reply_subject.indexOf("Re: ") < 0){
            reply_subject = 'Re: '+ reply_subject;
        }
        
        var emctr = parseInt($("#emailcnt").val()),
            reqmsg = parseInt($("#noOfEmails").val());
            emctr = emctr + 1;
            $("#emailcnt").val(emctr);
        
            if(reqmsg == emctr){
                console.log('generate now');
                setTimeout(function(){
                    $('#tbl_records').dataTable({
                        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                        "aoColumnDefs" : [{'bSortable' : false, 'aTargets' : [-1]}],
                        "aaSorting": [4, 'desc']
                    });
                    
                    $('#section_records').show();
                    $('#screen_loading').hide();
                    $('.table-inbox').removeClass("hidden");
                    $('#btnExport').removeClass("hidden");
                    //$('#assignToNotes').removeClass("hidden");
                    console.log('table is generated');
                }, 1000);
            }
            
            
        var msg_params = {
            profile_id: $("#connection_profile").text(),
            msg_message_id: message.id,
            msg_raw_message_id: getHeader(message.payload.headers, 'Message-ID'),
            msg_thread_id: message.threadId,
            msg_body: getBody(message.payload),
            msg_body_mime: message.payload.mimeType,
            msg_subject: subject,
            msg_to: to,
            msg_from: from,
            msg_reply_to: reply_to,
            msg_in_reply_to: getHeader(message.payload.headers, 'In-Reply-To'),
            msg_date: date,
            msg_microtime: message.internalDate,
            msg_snippet: snippet,
            msg_attachments: attachments
        }
        
        msg_params = btoa(unescape(encodeURIComponent(JSON.stringify(msg_params))));
        msg_orig = btoa(unescape(encodeURIComponent(JSON.stringify(message.payload))));
        
            modal += '<div class="modal fade" id="message-modal-' + message.id + '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">';
            modal += '<div class="modal-dialog modal-lg">';
            modal += '<div class="modal-content">';
            modal += '<div class="modal-body">';
            modal += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
            modal += '<span style="font-size:16px; font-weight:700" class="modal-title" id="myModalLabel">' + subject + '</span><hr/>';
            modal += '<div class="row">';
            modal += '<div class="col-lg-8 text-left">';
            modal += '<span style="font-size:12px;">From: <i>' + from + '</i></span>';
            modal += '</div>';
            modal += '<div class="col-lg-4 text-right">';
            modal += '<span style="font-size:12px;">' + moment.unix(message.internalDate/1000).format("DD MMM YYYY HH:mm A") + '</span>';
            modal += '</div>';

            modal += '<div class="col-lg-12">';
            modal += '<span style="font-size:12px;">To: <i>' + to + '</i></span>';
            modal += '</div>';
            modal += '</div>';
            modal += '<hr/><iframe id="message-iframe-'+message.id+'" srcdoc="<p>Loading...</p>"></iframe><hr/>';
            
            if(attachments.length > 0){
                modal += '<div class="row">';
                modal += '<div class="col-lg-12"><small>';
                for(var b=0; b < attachments.length; b++){
                    modal += attachments[b].atch_filename + "; ";
                }
                modal += '</small></div>';
                modal += '</div><hr/>';    
            }
            
            modal += '<div class="row">';
            modal += '<div class="col-lg-12">';
            //modal += '<button type="button" class="btn btn-default pull-right" data-dismiss="modal" style="margin-left:1%;">Close</button>';
            modal += '<button type="button" id="btnOptReply_'+message.id+'" class="btn btn-primary reply-button pull-right" data-dismiss="modal" data-toggle="modal" data-target="#reply-modal" style="margin-left:1%;" onclick="fillInReply(\''+reply_to+'\', \''+ reply_subject.replace(/'/g, "\\'") +'\', \''+ from +'\', \''+ date +'\',  \''+getHeader(message.payload.headers, 'Message-ID')+'\', \''+ msg_orig +'\' );">Reply to Email</button>';
            modal += '<button type="button" id="btnOptSave_'+message.id+'" class="btn btn-success reply-button pull-right" data-dismiss="modal" data-toggle="modal" style="margin-left:1%;" onclick="fillInMoveToNotes(\'' + msg_params + '\');">Save to Notes</button>';
            //modal += '<button type="button" id="btnOptReplySave" data-dismiss="modal" data-toggle="modal" data-target="#movetonotes-modal" style="display:none"></button>';
            modal += '</div>';
            modal += '</div>';
            modal += '</div>';
            modal += '</div>';
            modal += '</div>';
            modal += '</div>';
            
            $('body').append(modal);
        
            $('#message-link-'+message.id).on('click', function(){
                //var ifrm = $('#message-iframe-'+message.id)[0].contentWindow.document;
                //$('body', ifrm).html(getBody(message.payload));
                
                var newWindow = window.open("", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=40,left=220,width=900,height=560");
                    newWindow.document.write(getBody(message.payload));
                    newWindow.document.title = "Subject: " + subject;
            });

            $('#save-message-link-'+message.id).on('click', function(){
                //var ifrm = $('#message-iframe-'+message.id)[0].contentWindow.document;
                //console.log(ifrm);
                //$('body', ifrm).html(getBody(message.payload));
                $("#btnOptSave_"+message.id).click();
                
                //var newWindow = window.open("", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=300,width=800,height=400");;
                //newWindow.document.write(getBody(message.payload));
            });
            
            $('#reply-message-link-'+message.id).on('click', function(){
                //var ifrm = $('#message-iframe-'+message.id)[0].contentWindow.document;
                //console.log(ifrm);
                //$('body', ifrm).html(getBody(message.payload));
                $("#btnOptReply_"+message.id).click();
                
                //var newWindow = window.open("", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=300,width=800,height=400");;
                //newWindow.document.write(getBody(message.payload));
            });

    }
    
    function sendEmail() {
        $('#send-button').addClass('disabled');
        sendMessage({
            'To': $('#compose-to').val(),
            'Subject': $('#compose-subject').val()
        }, $('#compose-message').val(), composeTidy);
        return false;
    }

    function composeTidy(){
        $('#compose-modal').modal('hide');
        $('#compose-to').val('');
        $('#compose-subject').val('');
        $('#compose-message').val('');
        $('#send-button').removeClass('disabled');
    }

    function sendReply(){
        $('#reply-button').addClass('disabled');
        //'Cc': 'adrian.silva@lophils.com',
        //'Bcc': 'it@smallbuilders.com.au',
        
        sendMessage({
            'To': $('#reply-to').val(),
            'Subject': $('#reply-subject').val(),
            'In-Reply-To': $('#reply-message-id').val(),
            'Message-ID': $('#reply-message-id').val(),
            'Content-Type': 'text/html; charset=utf-8'

        }, $('#reply-message').val(), replyTidy);
        return false;
    }

    function replyTidy(){
        var newMsgId = arguments[0].result.id,
            newThreadId = arguments[0].result.threadId;
        
        $('#repliedMsgId').val(newMsgId);
        $('#repliedThreadId').val(newThreadId);
        
        $("#replyMessageResult").text("Email successfully sent.");
        $("#divReplyMessage").addClass("alert-success");
        $("#divReplyMessage").css("display", "block");
        
        setTimeout(function(){
            //pre-fill saving notes page
            $('#mnotes-subject').text($('#reply-subject').val());
            $('#mnotes-subject').css('display', 'none');
            $('#saveReplyTitle').text('Save Replied Email to Notes');
            $("#mnotes-snippet").val($('#reply-message').val());
            $("#projectname").val('');
            $("#status").val('');
            $("#personresponsible").val('');
            $("#personresponsible").trigger('chosen:updated');
            $("#duedate").val('');
            $("#discussion").val('');
            $("#discussion").trigger('chosen:updated');
            $('#btnSaveToNotes').hide();
            $('#btnReplySave').show();
            $('#reply-modal').modal('hide');
            $('#reply-button').removeClass('disabled');
            $('#movetonotes-modal').modal('show');
        }, 1000);
    }

    function fillInReply(to, subject, from, date, reply_message_id, msg_orig){
        $("#replyMessageResult").text("");
        $("#divReplyMessage").css("display", "none");
        $("#divReplyMessage").removeClass("alert-success");
        $("#divReplyMessage").removeClass("alert-danger");
        $('#reply-to').val(to);
        $('#reply-subject').val(subject);
        $('#reply-message-id').val(reply_message_id);
        $('#reply-from').val(from);
        $('#reply-date_received').val(date);
        $('#reply-previous-message').val(msg_orig);
        $('#repliedMsgId').val('');
        $('#reply-message').val('');
    }
    
    function fillInMoveToNotes(msg_params){
        var msg = JSON.parse(decodeURIComponent(escape(atob(msg_params))));
            setTimeout(function(){ 
                $('#btn-open-modal-move-to-notes').click();
            }, 500);
            $('#btnSaveToNotes').show();
            $('#btnReplySave').hide();
            $("#moveToNotesResult").text('');
            $("#projectname").val('');
            $("#mnotes-snippet").val('');
            $("#status").val('');
            $("#personresponsible").val('');
            $("#personresponsible").trigger('chosen:updated');
            $("#duedate").val('');
            $("#discussion").val('');
            $("#discussion").trigger('chosen:updated');
            $("#divMoveToNotes").css("display", "none");
            $("#divMoveToNotes").removeClass("alert-success");
            $("#divMoveToNotes").removeClass("alert-danger");
            $('#mselected-msg').val(msg_params);
            $('#mnotes-subject').text(msg.msg_subject);
            $('#mnotes-subject').css('display', 'block');
            $('#saveReplyTitle').text('');
            $('#mnotes-snippet').val(msg.msg_snippet);
    }
    
    function sendMessage(headers_obj, message, callback){
        var email = '',
            msg_body = JSON.parse(decodeURIComponent(escape(atob($("#reply-previous-message").val())))),
            date_received = $('#reply-date_received').val(),
            received_from = $('#reply-from').val(),
            reply_src = '', joinedmsg = '',
            msg_body = getBody(msg_body);
            
            reply_src += '<br/><br/><div class="gmail_quote">On ' + date_received.trim() + ', <span dir="ltr">'+ received_from.trim() +'</b> wrote:<br>';
            reply_src += '<blockquote class="gmail_quote" style="margin:0 0 0 .8ex;border-left:1px #ccc solid;padding-left:1ex">';
            reply_src += msg_body;
            reply_src += '</blockquote></div>';
            
            joinedmsg += "\r\n" + message.replace(/\n/g, "<br />") + reply_src;

        for(var header in headers_obj)
            email += header += ": "+headers_obj[header]+"\r\n";
            email += joinedmsg;
            
            var sendRequest = gapi.client.gmail.users.messages.send({
                'userId': 'me',
                'resource': {
                    'raw': window.btoa(unescape(encodeURIComponent(email))).replace(/\+/g, '-').replace(/\//g, '_')
                }
            });

        $("#repliedMsg").val(btoa(unescape(encodeURIComponent(joinedmsg))));
        return sendRequest.execute(callback);
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
    <script src="https://apis.google.com/js/client.js?onload=handleClientLoad"></script>
  </body>
</html>
