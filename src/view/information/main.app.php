<?php include "../includes/header.php" ?>
<div id="layoutSidenav">
    <?php include "../includes/panel.php" ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Academic Information</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Manage Academic Information</li>
                </ol>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="filter">
                            <div class="d-flex align-items-center justify-content-between">
                                    <ul class="nav nav-pills" id="pills-tab" role="tablist" style="gap: 10px">
                                        <li class="nav-item" role="presentation" >
                                            <button class="nav-link active py-3 sits-link"  id="pills-course-tab" data-bs-toggle="pill" data-bs-target="#pills-course" type="button" role="tab" aria-controls="pills-course" aria-selected="true">
                                                Course
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link py-3 sits-link" id="pills-yas-tab" data-bs-toggle="pill" data-bs-target="#pills-yas" type="button" role="tab" aria-controls="pills-yas" aria-selected="false">
                                                Year & Section
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link py-3 sits-link" id="pills-dept-tab" data-bs-toggle="pill" data-bs-target="#pills-dept" type="button" role="tab" aria-controls="pills-dept" aria-selected="false">
                                                Department
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link py-3 sits-link" id="pills-sch-tab" data-bs-toggle="pill" data-bs-target="#pills-sch" type="button" role="tab" aria-controls="pills-sch" aria-selected="false">
                                                Scholarship Type
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-course" role="tabpanel" aria-labelledby="pills-course-tab" tabindex="0">
                            <?php include "course.php" ?>
                        </div>
                        <div class="tab-pane fade" id="pills-yas" role="tabpanel" aria-labelledby="pills-yas-tab" tabindex="1">
                            <?php include "yearsection.php" ?>
                        </div>
                        <div class="tab-pane fade" id="pills-dept" role="tabpanel" aria-labelledby="pills-dept-tab" tabindex="2">
                            <?php include "department.php" ?>
                        </div>
                        <div class="tab-pane fade" id="pills-sch" role="tabpanel" aria-labelledby="pills-sch-tab" tabindex="3">
                            <?php include "scholarship.php" ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>