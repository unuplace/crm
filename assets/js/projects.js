document.addEventListener('DOMContentLoaded', function() {
    // إضافة مستمعي الأحداث لأزرار إضافة وتعديل وحذف المشروع
    document.getElementById('addProjectBtn').addEventListener('click', addProject);
});

function addProject(event) {
    event.preventDefault();
    const form = document.getElementById('addProjectForm');
    const formData = new FormData(form);

    fetch('/api/add-project.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تمت إضافة المشروع بنجاح');
            location.reload();
        } else {
            alert('حدث خطأ أثناء إضافة المشروع');
        }
    })
    .catch(error => console.error('Error:', error));
}

function editProject(id) {
    // قم بتنفيذ استدعاء AJAX لجلب بيانات المشروع وملء نموذج التعديل
}

function deleteProject(id) {
    if (confirm('هل أنت متأكد من رغبتك في حذف هذا المشروع؟')) {
        fetch(`/api/delete-project.php?id=${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم حذف المشروع بنجاح');
                location.reload();
            } else {
                alert('حدث خطأ أثناء حذف المشروع');
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

document.querySelectorAll('.edit-project-btn').forEach(button => {
    button.addEventListener('click', function() {
      const projectId = this.dataset.projectId;
      fetch(`/api/get_project.php?id=${projectId}`)
        .then(response => response.json())
        .then(project => {
          document.getElementById('editProjectId').value = project.id;
          document.getElementById('editProjectName').value = project.name;
          document.getElementById('editProjectDescription').value = project.description;
          document.getElementById('editProjectStartDate').value = project.start_date;
          document.getElementById('editProjectEndDate').value = project.end_date;
          document.getElementById('editProjectUnitsSold').value = project.units_sold;
          document.getElementById('editProjectStatus').value = project.status;
          $('#editProjectModal').modal('show');
        });
    });
  });
  
  document.getElementById('editProjectForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('/api/update_project.php', {
      method: 'POST',
      body: formData
    })
      .then(response => response.json())
      .then(result => {
        if (result.success) {
          alert('تم تحديث المشروع بنجاح');
          location.reload();
        } else {
          alert('حدث خطأ أثناء تحديث المشروع');
        }
      });
  });