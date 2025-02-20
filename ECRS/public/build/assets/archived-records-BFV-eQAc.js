$(document).ready(function(){$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}});const a=window.innerWidth<768,n=document.querySelector("#archivedClassRecordFaculty");n&&$(n).DataTable({processing:!0,serverSide:!0,ajax:{url:"/get-faculty-archived"},type:"GET",dataType:"json",columns:[{data:"courseName",render:function(t,s,e){return`<div class=" font-bold">${t}</div>`}},{data:"courseCode",render:function(t,s,e){return`<div class=" font-bold">${t}</div>`}},{data:"null",render:function(t,s,e){return`<div class="font-bold">${e.programName} ${e.yearLevel} (${e.branch})</div>`}},{data:null,render:function(t,s,e){let r="Unknown Semester";return e.semester==1?r="1st Semester":e.semester==2?r="2nd Semester":e.semester==3&&(r="Summer Semester"),`<div class="font-bold">${r} (${e.schoolYear})</div>`}},{data:null,render:function(t,s,e){return`
                            <div class="relative group flex justify-center items-center">
                                <div class="flex justify-center items-center">
                                    <form action="/store-class-record-id" method="POST">
                        <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr("content")}">
                        <input type="hidden" name="classRecordID" value="${e.classRecordID}">
                        <button type="submit" class="cursor-pointer">
                            <i class="fa-solid fa-book text-blue-500 hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer text-lg"></i>
                        </button>
                    </form>
                                </div>
                                <div class="absolute bottom-full left-1/2 transform hidden group-hover:block -translate-x-1/2 z-40 mb-4">
                                    <div class="flex justify-center items-center text-center transition-all duration-300 relative">
                                        <span class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">View Info</span>
                                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]"></div>
                                    </div>
                                </div>
                            </div>`}}],scrollX:a,pagingType:"simple",paging:!0,pageLength:10,lengthMenu:[10,25,50],order:[]});const o=document.querySelector("#archivedClassRecordStudent");o&&$(o).DataTable({processing:!0,serverSide:!0,ajax:{url:"/get-student-archived"},type:"GET",dataType:"json",columns:[{data:"courseName",render:function(t,s,e){return`<div class=" font-bold">${t}</div>`}},{data:"courseCode",render:function(t,s,e){return`<div class=" font-bold">${t}</div>`}},{data:"null",render:function(t,s,e){return`<div class="font-bold">${e.programName} ${e.yearLevel}</div>`}},{data:null,render:function(t,s,e){let r="Unknown Semester";return e.semester==1?r="1st Semester":e.semester==2?r="2nd Semester":e.semester==3&&(r="Summer Semester"),`<div class="font-bold">${r} (${e.schoolYear})</div>`}},{data:null,render:function(t,s,e){return`
                            <div class="relative group flex justify-center items-center">
                                <div class="flex justify-center items-center">
                                    <form action="/store-stud-class-record-id" method="POST">
                        <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr("content")}">
                        <input type="hidden" name="classRecordIDView" value="${e.classRecordID}">
                        <button type="submit" class="cursor-pointer">
                            <i class="fa-solid fa-book text-blue-500 hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer text-lg"></i>
                        </button>
                    </form>
                                </div>
                                <div class="absolute bottom-full left-1/2 transform hidden group-hover:block -translate-x-1/2 z-40 mb-4">
                                    <div class="flex justify-center items-center text-center transition-all duration-300 relative">
                                        <span class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">View Info</span>
                                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]"></div>
                                    </div>
                                </div>
                            </div>`}}],scrollX:a,pagingType:"simple",paging:!0,pageLength:10,lengthMenu:[10,25,50],order:[]})});
