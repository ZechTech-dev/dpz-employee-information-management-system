console.log("Leave Request JS Loaded");

function openRequestModal(){
    const modal=document.getElementById("requestModal");

    if(modal){
        modal.style.display="flex";
        currentStep=1;
        showStep(currentStep);
    }
}

function closeRequestModal(){
    const modal=document.getElementById("requestModal");

    if(modal){
        modal.style.display="none";
    }
}

function closeLeaveModal(){
    const modal=document.getElementById("leaveModal");

    if(modal){
        modal.style.display="none";
    }
}

let currentStep=1;

function showStep(step){
    document.querySelectorAll(".step").forEach(function(el){
        el.classList.remove("active");
    });

    const current=document.getElementById("step"+step);

    if(current){
        current.classList.add("active");
    }

    document.querySelectorAll(".circle").forEach(function(el){
        el.classList.remove("active");
    });

    for(let i=1;i<=step;i++){
        const circle=document.getElementById("circle"+i);

        if(circle){
            circle.classList.add("active");
        }
    }
}

function nextStep(){

    if(currentStep===1){

        const type=document.getElementById("leaveType");

        if(!type || type.value===""){
            alert("Please select leave type.");
            return;
        }
    }

    if(currentStep===2){

        const start=document.getElementById("startDate");
        const end=document.getElementById("endDate");
        const reason=document.getElementById("reason");

        if(!start.value||!end.value||!reason.value.trim()){
            alert("Please complete all leave details.");
            return;
        }

        if(end.value<start.value){
            alert("End date cannot be before start date.");
            return;
        }

        updateReview();
    }

    if(currentStep<4){
        currentStep++;
        showStep(currentStep);
    }
}

function prevStep(){

    if(currentStep>1){
        currentStep--;
        showStep(currentStep);
    }
}

const startDate=document.getElementById("startDate");
const endDate=document.getElementById("endDate");
const duration=document.getElementById("duration");

if(startDate&&endDate){
    startDate.addEventListener("change",calculateDuration);
    endDate.addEventListener("change",calculateDuration);
}

function calculateDuration(){

    if(!startDate.value||!endDate.value){
        duration.value="";
        return;
    }

    const start=new Date(startDate.value);
    const end=new Date(endDate.value);

    const difference=(end-start)/(1000*60*60*24);

    if(difference>=0){
        duration.value=(difference+1)+" day(s)";
    }else{
        duration.value="Invalid date";
    }
}

function updateReview(){

    const leaveType=document.getElementById("leaveType");
    const start=document.getElementById("startDate");
    const end=document.getElementById("endDate");
    const reason=document.getElementById("reason");

    document.getElementById("reviewType").textContent=leaveType.value;
    document.getElementById("reviewFrom").textContent=start.value;
    document.getElementById("reviewTo").textContent=end.value;
    document.getElementById("reviewDuration").textContent=duration.value;
    document.getElementById("reviewReason").textContent=reason.value;
}

function openLeaveModal(id){

    fetch("/dpz-eims/process/emp/viewLeaveProcess.php?id="+encodeURIComponent(id))
        .then(function(response){

            if(!response.ok){
                throw new Error("Failed to load leave request.");
            }

            return response.json();
        })
        .then(function(data){

            if(data.error){
                alert(data.error);
                return;
            }

            document.getElementById("modalType").textContent=data.leave_type||"";
            document.getElementById("modalStatus").textContent=data.status||"";
            document.getElementById("modalFiled").textContent=data.applied_at||"";
            document.getElementById("modalFrom").textContent=data.start_date||"";
            document.getElementById("modalTo").textContent=data.end_date||"";
            document.getElementById("modalDuration").textContent=(data.duration||0)+" day(s)";
            document.getElementById("modalReason").textContent=data.reason||"";

            const modal=document.getElementById("leaveModal");

            if(modal){
                modal.style.display="flex";
            }
        })
        .catch(function(error){
            console.error(error);
            alert("Unable to load leave request details.");
        });
}

const statusFilter=document.getElementById("statusFilter");
const yearFilter=document.getElementById("yearFilter");

function applyFilters(){

    const status=statusFilter?statusFilter.value:"allRequest";
    const year=yearFilter?yearFilter.value:new Date().getFullYear();

    const params=new URLSearchParams();

    params.set("status",status);
    params.set("year",year);

    window.location.href="leaveRequest.php?"+params.toString();
}

if(statusFilter){
    statusFilter.addEventListener("change",applyFilters);
}

if(yearFilter){
    yearFilter.addEventListener("change",applyFilters);
}

function goToPage(page){

    const status=statusFilter?statusFilter.value:"allRequest";
    const year=yearFilter?yearFilter.value:new Date().getFullYear();

    const params=new URLSearchParams();

    params.set("page",page);
    params.set("status",status);
    params.set("year",year);

    window.location.href="leaveRequest.php?"+params.toString();
}

const leaveForm=document.getElementById("leaveForm");

if(leaveForm){

    leaveForm.addEventListener("submit",function(event){

        event.preventDefault();

        const submitButton=leaveForm.querySelector('button[type="submit"]');

        if(submitButton){
            submitButton.disabled=true;
            submitButton.textContent="Submitting...";
        }

        const formData=new FormData(leaveForm);

        fetch("/dpz-eims/process/leaveRequestProcess.php",{
            method:"POST",
            body:formData
        })
        .then(function(response){

            return response.text();

        })
        .then(function(responseText){

            console.log("PHP RESPONSE:");
            console.log(responseText);

            let data;

            try{
                data=JSON.parse(responseText);
            }catch(error){

                console.error("JSON ERROR:",error);
                console.error("SERVER RESPONSE:",responseText);

                alert("PHP did not return valid JSON. Check the browser console.");

                if(submitButton){
                    submitButton.disabled=false;
                    submitButton.textContent="Submit Request";
                }

                return;
            }

            if(data.success){

                alert(data.message||"Leave request submitted successfully.");

                closeRequestModal();

                leaveForm.reset();

                currentStep=1;
                showStep(currentStep);

                window.location.reload();

            }else{

                alert(data.message||"Failed to submit leave request.");

                if(submitButton){
                    submitButton.disabled=false;
                    submitButton.textContent="Submit Request";
                }
            }

        })
        .catch(function(error){

            console.error("AJAX ERROR:",error);

            alert("Something went wrong while submitting your leave request.");

            if(submitButton){
                submitButton.disabled=false;
                submitButton.textContent="Submit Request";
            }
        });
    });
}

window.addEventListener("click",function(event){

    const leaveModal=document.getElementById("leaveModal");
    const requestModal=document.getElementById("requestModal");

    if(event.target===leaveModal){
        closeLeaveModal();
    }

    if(event.target===requestModal){
        closeRequestModal();
    }
});

showStep(currentStep);