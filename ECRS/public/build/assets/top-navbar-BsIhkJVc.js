$(document).ready(function(){let n;$(document).ready(function(){$.ajax({url:"/get-role",type:"GET",dataType:"json",success:function(a){n=a.roleNum},error:function(a,r,t){console.error("Error fetching roleNum:",t)}})}),$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}}),$(document).ready(function(){const a=$("[data-profile]");a.on("click",function(r){r.stopPropagation();const t=$(this).data("profile"),e=$(`#${t}`);e.hasClass("hidden")?(e.removeClass("hidden"),$(this).addClass("bg-red-800 dark:bg-[#1E1E1E]")):(e.addClass("hidden"),$(this).removeClass("bg-red-800 dark:bg-[#1E1E1E]"))}),$(document).on("click",function(r){a.each(function(){const t=$(this).data("profile"),e=$(`#${t}`);!$(this).is(r.target)&&!e.is(r.target)&&e.has(r.target).length===0&&(e.addClass("hidden"),$(this).removeClass("bg-red-800 dark:bg-[#1E1E1E]"))})})}),$("#close-btn-profile-admin").on("click",function(){$("#admin-modal").addClass("hidden"),$("body").removeClass("no-scroll")}),$(document).ready(function(){const a=$("#notif-button"),r=$(".notif-container");a.on("click",function(t){t.stopPropagation(),r.hasClass("hidden")?(i(),d(),r.removeClass("hidden"),a.addClass("bg-red-800 dark:bg-[#1E1E1E]")):(r.addClass("hidden"),a.removeClass("bg-red-800 dark:bg-[#1E1E1E]"))}),$(document).on("click",function(t){!a.is(t.target)&&!r.is(t.target)&&r.has(t.target).length===0&&(r.addClass("hidden"),a.removeClass("bg-red-800 dark:bg-[#1E1E1E]"))})}),$(document).on("click","#myLogout",function(a){$("#loader-modal").removeClass("hidden"),$("body").addClass("no-scroll")}),$(document).on("click","#submitBtn",function(a){a.preventDefault();let r=new FormData(document.getElementById("esignatureForm")),t=document.getElementById("esign").files[0];if(!t){Swal.fire({icon:"error",title:"Error",text:"Please select an e-signature image.",confirmButtonText:"OK"});return}r.append("esign",t),$.ajax({url:"/store-esignature-faculty",type:"POST",data:r,processData:!1,contentType:!1,success:function(e){e.success?Swal.fire({icon:"success",title:"Success",text:e.message,confirmButtonText:"OK"}):Swal.fire({icon:"error",title:"Error",text:e.message,confirmButtonText:"OK"})},error:function(){Swal.fire({icon:"error",title:"Error",text:"There was an error submitting the form. Please try again.",confirmButtonText:"OK"})}})}),$(document).on("click","#submitBtnAdmin",function(a){a.preventDefault();let r=new FormData(document.getElementById("esignatureFormAdmin")),t=document.getElementById("esignAdmin").files[0];if(!t){Swal.fire({icon:"error",title:"Error",text:"Please select an e-signature image.",confirmButtonText:"OK"});return}r.append("esignAdmin",t),$.ajax({url:"/store-esignature-admin",type:"POST",data:r,processData:!1,contentType:!1,success:function(e){e.success?Swal.fire({icon:"success",title:"Success",text:e.message,confirmButtonText:"OK"}):Swal.fire({icon:"error",title:"Error",text:e.message,confirmButtonText:"OK"})},error:function(){Swal.fire({icon:"error",title:"Error",text:"There was an error submitting the form. Please try again.",confirmButtonText:"OK"})}})});function i(){$.ajax({url:"/notifications",type:"GET",dataType:"json",success:function(a){const r=$(".notif-container");if(a.notifications.length>0){let t=`
                        <div class="rounded-lg overflow-hidden bg-white dark:bg-[#161616] border border-gray-300 dark:border-[#404040] shadow-lg">
                            <div class="shadow-lg rounded-lg p-4 h-96 w-72 sm:w-96 overflow-y-auto">
                                <div class="flex justify-between">
                                    <h3 class="text-lg font-bold mb-2 text-red-900 dark:text-[#CCAA2C] text-left">Notifications</h3>
                                    <div class="flex justify-center items-center">
                                        <div class="text-sm font-medium mb-2 text-red-900 dark:text-[#CCAA2C] cursor-pointer mark-all-as-read">
                                            Mark all as Read
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-2">`;a.notifications.forEach(e=>{let o="",s="",l="submit",u="POST";n==1?e.type==="grade_request"?(o="/mark-as-read",s=`
                                                <input type="hidden" name="classRecordIDRequest" value="${e.classRecordID}">
                                                <input type="hidden" name="notifID" value="${e.id}">
                                            `):e.type==="notif_verified"?(o="/notif/markasread-file",s=`
                                                <input type="hidden" name="notifIDVerified" value="${e.id}">
                                            `):e.type==="notice_faculty"&&(o="/store-class-record-id-notice",s=`
                                                <input type="hidden" name="notifIDNotice" value="${e.id}">
                                            `):n==2?e.type==="submit_grades"?(o="/store-file-id-notif",s=`
                                                <input type="hidden" name="notifIDAdmin" value="${e.id}">
                                            `):e.type==="faculty_loads"&&(o="#",s=`
                                                <input type="hidden" name="notifIDFacultyLoad" value="${e.id}">
                                            `,l="button"):n==3&&e.type==="publish_score"&&(o="/store-stud-class-record-id-notif",s=`
                                                <input type="hidden" name="classRecordIDStudentNotif" value="${e.classRecordID}">
                                                <input type="hidden" name="notifIDStudent" value="${e.id}">
                                            `),o?t+=`
                                            <form action="${o}" method="${u}">
                                                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr("content")}">
                                                ${s}
                                                <button type="${l}" class="p-3 border ${e.read_at?"border-gray-200 dark:border-[#404040]":"border-red-900 font-bold dark:border-[#CCAA2C]"} rounded-md dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E] text-black text-left">
                                                    <div class="flex flex-col"> 
                                                        <p>${e.message}</p>
                                                        <small class="text-gray-500">${e.created_at}</small>
                                                    </div>
                                                </button>
                                            </form>
                                        `:t+=`
                                            <div class="p-3 border ${e.read_at?"border-gray-200 dark:border-[#404040]":"border-red-900 font-bold dark:border-[#CCAA2C]"} rounded-md dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E] text-black text-left">
                                                <div class="flex flex-col"> 
                                                    <p>${e.message}</p>
                                                    <small class="text-gray-500">${e.created_at}</small>
                                                </div>
                                            </div>
                                        `}),t+="</div></div></div>",r.html(t).removeClass("hidden"),$(".mark-all-as-read").on("click",function(){c()})}else r.html(`
                        <div class="border border-gray-300 dark:border-[#404040] rounded-lg overflow-hidden bg-white dark:bg-[#161616]">
                            <div class="shadow-lg rounded-lg p-4 h-96 w-72 sm:w-96 overflow-y-auto flex justify-center items-center">
                                <p class="text-gray-600">No notifications</p>
                            </div>
                        </div>
                    `).removeClass("hidden")},error:function(a,r,t){console.error("Error fetching notifications:",t)}})}function d(){$.ajax({url:"/notifications",type:"GET",dataType:"json",success:function(a){const r=a.unreadCount,t=$("#notif-badge");r>0?t.html(`
                        <span
                            class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex justify-center items-center">
                            ${r}
                        </span>
                    `).show():t.empty().hide()},error:function(a,r,t){console.error("Error fetching unread count:",t)}})}$(document).ready(function(){d()});function c(){$.ajax({url:"/mark-all-as-read",type:"POST",headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},success:function(a){a.success?(i(),d()):console.error(a.message)},error:function(a,r,t){console.error("Error marking notifications as read:",t)}})}});
