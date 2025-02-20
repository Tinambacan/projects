$(document).ready(function(){$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}});function u(m){$("#"+m).fadeOut(),$("body").removeClass("no-scroll")}function c(m,i){$("#"+m).show(),$("body").addClass("no-scroll"),$("#add-assessment-form").attr("data-assessment-type",i)}$("[id^=add-]").on("click",function(){var m=$(this).attr("id").replace("add-","").replace("-btn",""),i="add-"+m+"-modal";c(i,m)});const a=document.querySelector("#assessInfoTable"),p=window.innerWidth<768,o=document.getElementById("isArchived").textContent.trim()==="1";if(a){let g=function(){const t=x();$("#selectedAssessIDs").text(t.join(", "))},x=function(){const t=[];return l.rows().every(function(){const e=$(this.node());if(e.find('input[type="checkbox"].assess_checkbox').prop("checked")){const r=e.find('input[type="checkbox"]').data("assess-id");r&&t.push(r)}}),[...new Set(t)]};var h=g,f=x;let m="",i="",l;l=$(a).DataTable({processing:!0,serverSide:!0,ajax:{url:"/get-assessment-info",type:"GET",dataType:"json",headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},dataSrc:function(t){return i=t.storedAssessmentType,m=t.gradingDistributionType,t.data}},columns:[{data:null,render:function(t,n,e){return`<input type="checkbox" class="assess_checkbox text-center" data-assess-id="${e.assessmentID}" data-name="${e.assessmentName}" ${e.isPublished?"disabled":""}>`},orderable:!1},{data:"assessmentName"},{data:"totalItem",render:function(t,n,e){return i!=="attendance"?`<div class="text-center">${t}</div>`:`<div class="text-center">${e.assessmentDate}</div>`}},{data:"passingItem",render:function(t,n,e){if(i!=="attendance")return`<div class="text-center">${t}</div>`;let d="";return e.isPublished==1?d='<span class="bg-green-500 text-white p-2 rounded-md">Published</span>':d=`
                                <div class="relative group flex justify-center items-center">
                                    <button class="send-stud-scores cursor-pointer" data-assessment-id="${e.assessmentID}">
                                         <span class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md">Unpublished</span>
                                    </button>
                                   
                              <div
                                class="absolute top-[-55px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                <div
                                    class="flex justify-center items-center text-center transition-all duration-300 relative">
                                    <span class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">Publish score</span>
                                    <div
                                        class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                                    </div>
                                </div>
                                </div>
                            `,`<div class="text-center">${d}</div>`}},{data:"assessmentDate",render:function(t,n,e){return i!=="attendance"&&t?`<div class="text-center">${t}</div>`:`
                            <div class="text-center text-xl flex gap-1 justify-center items-center">

                                <!-- Form for assessment ID submission -->
                                <div class="relative group flex justify-center items-center">
                                    <form action="/store-assessment-id" method="POST">
                                        <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr("content")}">
                                        <input type="hidden" name="assessmentID" value="${e.assessmentID}">
                                        <input type="hidden" name="gradingDistributionType" value="${m}">
                                        <input type="hidden" name="assessmentType" value="${i}">
                                        <button type="submit" class="text-white hover:bg-gray-200 hover:rounded-md p-1 text-center w-full flex justify-center">
                                            <i class="fa-solid fa-book text-blue-500"></i>
                                        </button>
                                    </form>

                                    <div class="absolute top-[-60px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                        <div class="flex justify-center items-center text-center transition-all duration-300 relative">
                                            <span class="p-2 text-xs text-white bg-[#404040] shadow-lg rounded-md">View Details</span>
                                            <div class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Button -->

                                ${o?"":`
                                <div class="relative group flex justify-center items-center">
                                    <i class="fa-solid fa-pen-to-square text-green-500 edit-assessment hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer"
                                       data-assessment-id="${e.assessmentID}"
                                       data-assessment-name="${e.assessmentName}"
                                       data-assessment-date="${e.assessmentDate}"
                                       data-total-item="${e.totalItem}"
                                       data-passing-item="${e.passingItem}"
                                       data-assessment-type="${i}">
                                    </i>

                                    <!-- Tooltip for "Edit Info" -->
                                    <div class="absolute top-[-60px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                        <div class="flex justify-center items-center text-center transition-all duration-300 relative">
                                            <span class="p-2 text-xs text-white bg-[#404040] shadow-lg rounded-md">Edit Info</span>
                                            <div class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]"></div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Duplicate Button -->
                                <div class="relative group flex justify-center items-center">
                                    <i class="fa-solid fa-copy text-yellow-500 duplicate-assessment hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer"
                                       data-assessment-id="${e.assessmentID}"
                                       data-assessment-name="${e.assessmentName}"
                                       data-assessment-date="${e.assessmentDate}"
                                       data-total-item="${e.totalItem}"
                                       data-passing-item="${e.passingItem}"
                                       data-assessment-type="${i}">
                                    </i>

                                    <!-- Tooltip for "Duplicate Info" -->
                                    <div class="absolute top-[-60px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                        <div class="flex justify-center items-center text-center transition-all duration-300 relative">
                                            <span class="p-2 text-xs text-white bg-[#404040] shadow-lg rounded-md">Duplicate Info</span>
                                            <div class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]"></div>
                                        </div>
                                    </div>
                                </div>
                                `}
                            </div>
                        `}},{data:"isPublished",render:function(t,n,e){return t==1?'<span class="bg-green-500 text-white p-2 rounded-md">Published</span>':`
                                <div class="relative group flex justify-center items-center">
                                    <button class="send-stud-scores cursor-pointer" data-assessment-id="${e.assessmentID}">
                                        <span class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md">Unpublished</span>
                                    </button>
                                   
                               <div class="absolute bottom-full left-1/2 transform hidden group-hover:block -translate-x-1/2 z-40 mb-4">
    <div class="flex justify-center items-center text-center transition-all duration-300 relative ">
        <span class="p-2 text-xs text-white bg-[#404040] shadow-lg rounded-md">Publish Score</span>
        <div
            class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
        </div>
    </div>
</div>
                            `}},{data:null,visible:i!=="attendance",render:function(t,n,e){return`
    <div class="text-center text-xl flex gap-1 justify-center items-center">
        <div class="relative group flex justify-center items-center">
            <form action="/store-assessment-id" method="POST">
                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr("content")}">
                <input type="hidden" name="assessmentID" value="${e.assessmentID}">
                <input type="hidden" name="gradingDistributionType" value="${m}">
                <input type="hidden" name="assessmentType" value="${i}">
                <button type="submit" class="text-white hover:bg-gray-200 hover:rounded-md p-1 text-center w-full flex justify-center">
                    <i class="fa-solid fa-book text-blue-500"></i>
                </button>
            </form>

            <div class="absolute top-[-60px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                <div class="flex justify-center items-center text-center transition-all duration-300 relative">
                    <span class="p-2 text-xs text-white bg-[#404040] shadow-lg rounded-md">View Details</span>
                    <div class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                    </div>
                </div>
            </div>
        </div>

        <!-- Conditionally include Edit Info -->
        ${o?"":`
        <div class="relative group flex justify-center items-center">
            <i class="fa-solid fa-pen-to-square text-green-500 edit-assessment hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer"
               data-assessment-id="${e.assessmentID}"
               data-assessment-name="${e.assessmentName}"
               data-assessment-date="${e.assessmentDate}"
               data-total-item="${e.totalItem}"
               data-passing-item="${e.passingItem}"
               data-assessment-type="${i}">
            </i>

            <div class="absolute top-[-60px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                <div class="flex justify-center items-center text-center transition-all duration-300 relative">
                    <span class="p-2 text-xs text-white bg-[#404040] shadow-lg rounded-md">Edit Info</span>
                    <div class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                    </div>
                </div>
            </div>
        </div>
        `}

        <!-- Conditionally include Duplicate Info -->
        ${o?"":`
        <div class="relative group flex justify-center items-center">
            <i class="fa-solid fa-copy text-yellow-500 duplicate-assessment hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer"
               data-assessment-id="${e.assessmentID}"
               data-assessment-name="${e.assessmentName}"
               data-assessment-date="${e.assessmentDate}"
               data-total-item="${e.totalItem}"
               data-passing-item="${e.passingItem}"
               data-assessment-type="${i}">
            </i>

            <div class="absolute top-[-60px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                <div class="flex justify-center items-center text-center transition-all duration-300 relative">
                    <span class="p-2 text-xs text-white bg-[#404040] shadow-lg rounded-md">Duplicate Info</span>
                    <div class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                    </div>
                </div>
            </div>
        </div>
        `}
    </div>
`},orderable:!1}],scrollX:p,pagingType:"simple",order:[],columnDefs:[{targets:[0],orderable:!1},...o?[{targets:[0,5,6],visible:!1}]:[]],initComplete:function(){o===1?(l.column(5).visible(!1),l.column(6).visible(!1)):(l.column(5).visible(i!=="attendance"),l.column(6).visible(i!=="attendance"))}}),$("#add-assessment-form").on("submit",function(t){t.preventDefault();var n=$(this).attr("action"),e=$(this).data("assessment-type"),d="add-"+e.toLowerCase().replace(/ /g,"-");$.ajax({url:n,method:"POST",data:$(this).serialize(),success:function(r){Swal.fire({title:"Success!",text:r.message,icon:"success",confirmButtonText:"OK"}).then(s=>{s.isConfirmed&&($("body").removeClass("no-scroll"),l.ajax.reload(),$("#add-assessment-form")[0].reset(),$("#"+d).fadeOut(),$("#char-count").text(20))})},error:function(r){if(r.status===409){var s=r.responseJSON.message;Swal.fire({title:"Error!",text:s,icon:"error",confirmButtonText:"OK"})}else{var v=r.responseJSON.errors,s=`Please fix the following errors:
`;$.each(v,function(S,y){s+=y.join(" ")+`
`}),Swal.fire({title:"Error!",text:s,icon:"error",confirmButtonText:"OK"})}}})}),$("#import-assessment-form").on("submit",function(t){t.preventDefault();var n=$(this).attr("action"),e="import-assessment-modal",d=new FormData(this);$.ajax({url:n,method:"POST",data:d,processData:!1,contentType:!1,success:function(r){Swal.fire({title:"Success!",text:r.message,icon:"success",confirmButtonText:"OK"}).then(s=>{s.isConfirmed&&($("#"+e).fadeOut(),$("body").removeClass("no-scroll"),$("#import-assessment-form")[0].reset(),l.ajax.reload())})},error:function(r){var s=r.responseJSON.message||"An unexpected error occurred.";Swal.fire({title:"Error!",text:s,icon:"error",confirmButtonText:"OK"})},complete:function(){$("#"+e).fadeOut()}})}),$("#edit-assessment-form").on("submit",function(t){t.preventDefault();var n=$(this).serialize();$.ajax({url:$(this).attr("action"),type:"POST",data:n,success:function(e){Swal.fire({title:"Success!",text:e.message,icon:"success",confirmButtonText:"OK"}).then(()=>{$("#edit-"+e.assessmentType.toLowerCase().replace(/\s+/g,"-")+"-modal").fadeOut(),$("body").removeClass("no-scroll"),l.ajax.reload()})},error:function(e){var d=e.responseJSON,r=d.message||"An error occurred.";d.invalidStudentAssessments&&d.invalidStudentAssessments.length>0&&(r+=`

Total score cannot be less than the score of the following students:
`,d.invalidStudentAssessments.forEach(function(s){r+=`${s.studentLname} (Score: ${s.studentScore})
`})),Swal.fire({title:"Error!",text:r,icon:"error",confirmButtonText:"OK"})}})}),$("#duplicate-assessment-form").on("submit",function(t){t.preventDefault();var n=$(this).serialize();$.ajax({url:$(this).attr("action"),type:"POST",data:n,success:function(e){Swal.fire({title:"Success!",text:e.message,icon:"success",confirmButtonText:"OK"}).then(()=>{$("#duplicate-"+e.assessmentType.toLowerCase().replace(/\s+/g,"-")+"-modal").fadeOut(),$("body").removeClass("no-scroll"),l.ajax.reload()})},error:function(e){var d=e.responseJSON,r=d.message||"An error occurred.";Swal.fire({title:"Error!",text:r,icon:"error",confirmButtonText:"OK"})}})}),$(document).on("click",".send-stud-scores",function(){const t=[$(this).data("assessment-id")],n=$('input[name="classRecordIDScore"]').val(),e=$('input[name="gradingType"]').val(),d=$('input[name="gradingTerm"]').val();if(t.length===0){Swal.fire({title:"Error!",text:"Please select at least one assessment",icon:"error",confirmButtonText:"OK"});return}$("body").addClass("no-scroll"),Swal.fire({title:"Publish scores?",icon:"question",showCancelButton:!0,confirmButtonText:"Publish",cancelButtonText:"No, cancel"}).then(r=>{$("#send-scores-loader").removeClass("hidden"),r.isConfirmed?$.ajax({url:"/notify-students-publish",method:"POST",data:{selectedAssessIDs:t,classRecordID:n,gradingType:e,gradingTerm:d,_token:$('meta[name="csrf-token"]').attr("content")},success:function(s){setTimeout(function(){Swal.fire({title:"Success!",text:s.message,icon:"success",confirmButtonText:"OK"}).then(function(){l.ajax.reload()})},500)},error:function(s){if(s.responseJSON&&s.responseJSON.invalidStudentAssessments){let v="";s.responseJSON.invalidStudentAssessments.forEach(function(b){v+=`Assessment: <strong>${b.assessmentName}</strong>, Student: <strong>${b.studentFname} ${b.studentLname}</strong><br>`}),Swal.fire({title:"Publish scores failed!",html:`<strong>Some students have no scores:</strong><br><br>${v}`,icon:"warning",confirmButtonText:"OK"})}else Swal.fire({title:"Error!",text:s.responseJSON?s.responseJSON.message:"An error occurred while sending the notification.",icon:"error",confirmButtonText:"OK"})},complete:function(){$("#send-scores-loader").addClass("hidden"),$("body").removeClass("no-scroll")}}):($("#send-scores-loader").addClass("hidden"),$("body").removeClass("no-scroll"))})}),$(".send-batch-stud-scores").click(function(){const t=x(),n=$('input[name="classRecordIDScore"]').val();$("#selectedAssessIDs").val(t);const e=$('input[name="gradingType"]').val(),d=$('input[name="gradingTerm"]').val();if(t.length===0){Swal.fire({title:"Error!",text:"Please select at least one assessment",icon:"error",confirmButtonText:"OK"});return}$("body").addClass("no-scroll"),Swal.fire({title:"Publish scores?",icon:"question",showCancelButton:!0,confirmButtonText:"Publish",cancelButtonText:"No, cancel"}).then(r=>{$("#send-scores-loader").removeClass("hidden"),r.isConfirmed?$.ajax({url:"/notify-students-batch",method:"POST",data:{selectedAssessIDs:t,classRecordID:n,gradingType:e,gradingTerm:d,_token:$('meta[name="csrf-token"]').attr("content")},success:function(s){setTimeout(function(){Swal.fire({title:"Success!",text:s.message,icon:"success",confirmButtonText:"OK"}).then(function(){l.ajax.reload()})},500)},error:function(s){if(s.responseJSON&&s.responseJSON.invalidStudentAssessments){let v="";s.responseJSON.invalidStudentAssessments.forEach(function(b){v+=`Assessment: <strong>${b.assessmentName}</strong>, Student: <strong>${b.studentFname} ${b.studentLname}</strong><br>`}),Swal.fire({title:"Publish scores failed!",html:`<strong>Some students have no scores:</strong><br><br>${v}`,icon:"warning",confirmButtonText:"OK"})}else Swal.fire({title:"Error!",text:s.responseJSON?s.responseJSON.message:"An error occurred while sending the notification.",icon:"error",confirmButtonText:"OK"})},complete:function(){$("#send-scores-loader").addClass("hidden"),$("body").removeClass("no-scroll")}}):($("#send-scores-loader").addClass("hidden"),$("body").removeClass("no-scroll"))})}),$("#assess_select_all").on("click",function(){const t=$(this).prop("checked");l.rows({page:"current"}).every(function(){$(this.node()).find('input[type="checkbox"].assess_checkbox:not(:disabled)').prop("checked",t)}),g()}),$("#myTable").on("change",'input[type="checkbox"].assess_checkbox',function(){g();const t=l.rows().count(),n=x().length,e=$("#assess_select_all").get(0);n===t?(e.checked=!0,e.indeterminate=!1):n===0?(e.checked=!1,e.indeterminate=!1):e.indeterminate=!0})}$(document).on("click","[id^=close-btn-]",function(){var m=$(this).attr("id"),i=m.replace("close-btn-","")+"-modal";u(i)}),$(document).ready(function(){$(".import-assessment-btn").on("click",function(){$("#import-assessment-modal").show(),$("body").addClass("no-scroll")}),$("#close-btn-import-assessment").on("click",function(){$("#import-assessment-modal").fadeOut(),$("body").removeClass("no-scroll")})})});function w(u,c,a){$(u).on("input",function(){const h=$(this).val().length,f=a-h;$(c).text(`${f}`),h>a&&($(this).val($(this).val().substring(0,a)),$(c).text(0))});const p=$(u).val().length,o=a-p;$(c).text(`${o}`)}$(document).on("click",".edit-assessment",function(){var u=$(this).data("assessment-id"),c=$(this).data("assessment-name"),a=$(this).data("total-item"),p=$(this).data("passing-item"),o=p/a*100,h=$(this).data("assessment-date"),f=$(this).data("assessment-type");$("#edit-assessment-id").val(u),$("#edit-"+f.toLowerCase().replace(/ /g,"-")+"-name").val(c),$("#edit-"+f.toLowerCase().replace(/ /g,"-")+"-date").val(h),$("#edit-"+f.toLowerCase().replace(/ /g,"-")+"-total").val(a),$("#edit-"+f.toLowerCase().replace(/ /g,"-")+"-passing").val(o),$("#edit-"+f.toLowerCase().replace(/ /g,"-")+"-modal").show(),$("body").addClass("no-scroll"),w("#edit-"+f.toLowerCase().replace(/ /g,"-")+"-name","#edit-char-count",20)});function T(u,c,a){$(u).on("input",function(){const h=$(this).val().length,f=a-h;$(c).text(`${f}`),h>a&&($(this).val($(this).val().substring(0,a)),$(c).text(0))});const p=$(u).val().length,o=a-p;$(c).text(`${o}`)}$(document).on("click",".duplicate-assessment",function(){var u=$(this).data("assessment-name"),c=$(this).data("total-item"),a=$(this).data("passing-item"),p=$(this).data("assessment-date"),o=$(this).data("assessment-type");$("#duplicate-"+o.toLowerCase().replace(/ /g,"-")+"-name").val(u),$("#duplicate-"+o.toLowerCase().replace(/ /g,"-")+"-date").val(p),$("#duplicate-"+o.toLowerCase().replace(/ /g,"-")+"-total").val(c),$("#duplicate-"+o.toLowerCase().replace(/ /g,"-")+"-passing").val(a),$("#duplicate-"+o.toLowerCase().replace(/ /g,"-")+"-modal").show(),$("body").addClass("no-scroll"),T("#duplicate-"+o.toLowerCase().replace(/ /g,"-")+"-name","#duplicate-char-count",20)});$(".close-btn").on("click",function(){var u=$(this).data("assessment-type");$("#edit-"+u.toLowerCase().replace(/ /g,"-")+"-modal").fadeOut(),$("body").removeClass("no-scroll")});$(document).ready(function(){$(".export-template-btn").on("submit",function(u){u.preventDefault(),Swal.fire({title:"Confirmation",text:"You are about to export a template",icon:"info",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Export"}).then(c=>{if(c.isConfirmed){$("#export-template").removeClass("hidden");const a=new FormData(this);$.ajax({url:"/export-assessment-template",method:"POST",data:a,processData:!1,contentType:!1,xhrFields:{responseType:"blob"},success:function(p,o,h){const f=h.getResponseHeader("Content-Disposition");let m="exported-scores.xlsx";if(f){const g=/filename="([^"]*)"/.exec(f);g!=null&&g[1]&&(m=g[1])}const i=new Blob([p],{type:h.getResponseHeader("Content-Type")}),l=document.createElement("a");l.href=window.URL.createObjectURL(i),l.download=m,document.body.appendChild(l),l.click(),document.body.removeChild(l),$("#export-template").addClass("hidden"),Swal.fire({title:"Success!",text:"The template has been exported successfully.",icon:"success",confirmButtonColor:"#3085d6"})},error:function(){Swal.fire({title:"Error!",text:"Something went wrong while exporting the template.",icon:"error",confirmButtonColor:"#d33"})}})}})})});$(document).ready(function(){const c="#assessmentName",a="#char-count";$(document).on("input",c,function(){const o=20-$(this).val().length;console.log(c),$(a).text(o),o<0&&($(this).val($(this).val().substring(0,20)),$(a).text(0))})});
