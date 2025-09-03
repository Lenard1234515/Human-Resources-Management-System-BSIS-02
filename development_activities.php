<?php
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once 'dp.php';

// Mock data for development activities (replace with actual database queries)
function getDevelopmentActivities() {
    return [
        [
            'id' => 1,
            'employee_name' => 'John Doe',
            'activity_title' => 'Leadership Workshop',
            'description' => 'Interactive workshop on effective leadership skills and team management.',
            'type' => 'Workshop',
            'start_date' => '2024-01-20',
            'end_date' => '2024-01-20',
            'status' => 'Completed',
            'progress' => 100,
            'assigned_by' => 'HR Manager',
            'location' => 'Conference Room A'
        ],
        [
            'id' => 2,
            'employee_name' => 'Jane Smith',
            'activity_title' => 'Advanced Python Programming',
            'description' => 'Comprehensive course on advanced Python concepts and best practices.',
            'type' => 'Online Course',
            'start_date' => '2024-02-01',
            'end_date' => '2024-03-01',
            'status' => 'In Progress',
            'progress' => 75,
            'assigned_by' => 'Department Head',
            'location' => 'Online Platform'
        ],
        [
            'id' => 3,
            'employee_name' => 'Mike Johnson',
            'activity_title' => 'Project Management Certification',
            'description' => 'Preparation course for PMP certification with practical project scenarios.',
            'type' => 'Certification',
            'start_date' => '2024-03-15',
            'end_date' => '2024-05-15',
            'status' => 'Scheduled',
            'progress' => 0,
            'assigned_by' => 'Supervisor',
            'location' => 'Training Center'
        ],
        [
            'id' => 4,
            'employee_name' => 'Sarah Wilson',
            'activity_title' => 'Communication Skills Seminar',
            'description' => 'Seminar focusing on effective communication in professional environments.',
            'type' => 'Seminar',
            'start_date' => '2024-04-10',
            'end_date' => '2024-04-10',
            'status' => 'Active',
            'progress' => 50,
            'assigned_by' => 'HR Manager',
            'location' => 'Auditorium'
        ]
    ];
}

function getActivityStats() {
    return [
        'total_activities' => 24,
        'completed_activities' => 12,
        'active_activities' => 8,
        'scheduled_activities' => 4
    ];
}

$activities = getDevelopmentActivities();
$stats = getActivityStats();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Development Activities - HR Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css?v=rose">
    <style>
        .progress-bar-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-completed {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }

        .status-in-progress {
            background: rgba(255, 193, 7, 0.1);
            color: var(--warning);
        }

        .status-scheduled {
            background: rgba(23, 162, 184, 0.1);
            color: var(--info);
        }

        .status-active {
            background: rgba(0, 123, 255, 0.1);
            color: var(--primary);
        }

        .activity-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.1);
        }

        .activity-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(233, 30, 99, 0.2);
        }

        .stats-gradient {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        }

        .timeline-item {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--primary-color);
        }

        .timeline-item::after {
            content: '';
            position: absolute;
            left: 5px;
            top: 12px;
            width: 2px;
            height: calc(100% - 12px);
            background: var(--border-light);
        }

        .timeline-item:last-child::after {
            display: none;
        }

        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(233, 30, 99, 0.25);
        }

        .btn-add-activity {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-add-activity:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(233, 30, 99, 0.3);
        }

        .filter-section {
            background: var(--bg-card);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .activity-type-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .type-workshop { background: linear-gradient(135deg, #FF6B6B, #EE5A24); }
        .type-course { background: linear-gradient(135deg, #4ECDC4, #44A08D); }
        .type-certification { background: linear-gradient(135deg, #45B7D1, #96CEB4); }
        .type-seminar { background: linear-gradient(135deg, #F7DC6F, #F39C12); }
    </style>
</head>
<body>
    <div class="container-fluid">
        <?php include 'navigation.php'; ?>
        <div class="row">
            <?php include 'sidebar.php'; ?>
            <div class="main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="section-title">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Development Activities
                    </h2>
                    <button class="btn btn-add-activity" data-toggle="modal" data-target="#addActivityModal">
                        <i class="fas fa-plus mr-2"></i>
                        Schedule New Activity
                    </button>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-alt text-primary"></i>
                                <h6 class="text-muted mt-2">Total Activities</h6>
                                <h3 class="card-title"><?php echo $stats['total_activities']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-check-circle text-success"></i>
                                <h6 class="text-muted mt-2">Completed</h6>
                                <h3 class="card-title"><?php echo $stats['completed_activities']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-play-circle text-info"></i>
                                <h6 class="text-muted mt-2">Active</h6>
                                <h3 class="card-title"><?php echo $stats['active_activities']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-clock text-warning"></i>
                                <h6 class="text-muted mt-2">Scheduled</h6>
                                <h3 class="card-title"><?php echo $stats['scheduled_activities']; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="filter-section">
                    <div class="row">
                        <div class="col-md-3">
                            <select class="form-control">
                                <option>All Status</option>
                                <option>Active</option>
                                <option>In Progress</option>
                                <option>Scheduled</option>
                                <option>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control">
                                <option>All Types</option>
                                <option>Workshop</option>
                                <option>Online Course</option>
                                <option>Certification</option>
                                <option>Seminar</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control">
                                <option>All Employees</option>
                                <option>John Doe</option>
                                <option>Jane Smith</option>
                                <option>Mike Johnson</option>
                                <option>Sarah Wilson</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary btn-block">
                                <i class="fas fa-search mr-2"></i>
                                Filter
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Development Activities Table -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-list mr-2"></i>
                        Development Activities Overview
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Activity</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Progress</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($activities as $activity): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="activity-icon mr-3">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($activity['employee_name']); ?></strong>
                                                    <br>
                                                    <small class="text-muted">Assigned by: <?php echo htmlspecialchars($activity['assigned_by']); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($activity['activity_title']); ?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo htmlspecialchars(substr($activity['description'], 0, 50)) . '...'; ?></small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="activity-type-icon type-<?php echo strtolower($activity['type']); ?> mr-2">
                                                    <i class="fas fa-<?php
                                                        switch($activity['type']) {
                                                            case 'Workshop': echo 'users'; break;
                                                            case 'Online Course': echo 'laptop'; break;
                                                            case 'Certification': echo 'certificate'; break;
                                                            case 'Seminar': echo 'chalkboard-teacher'; break;
                                                            default: echo 'calendar'; break;
                                                        }
                                                    ?>"></i>
                                                </div>
                                                <span><?php echo htmlspecialchars($activity['type']); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $activity['status'])); ?>">
                                                <?php echo htmlspecialchars($activity['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar progress-bar-custom" role="progressbar"
                                                     style="width: <?php echo $activity['progress']; ?>%"
                                                     aria-valuenow="<?php echo $activity['progress']; ?>" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small class="text-muted"><?php echo $activity['progress']; ?>%</small>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($activity['start_date'])); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($activity['end_date'])); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary" title="Edit Activity">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-success" title="Mark Complete">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities Timeline and Chart -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-history mr-2"></i>
                                Recent Activities
                            </div>
                            <div class="card-body">
                                <div class="timeline-item mb-3">
                                    <h6 class="mb-1">Workshop completed</h6>
                                    <small class="text-muted">John Doe's Leadership Workshop - 2 hours ago</small>
                                </div>
                                <div class="timeline-item mb-3">
                                    <h6 class="mb-1">Course milestone achieved</h6>
                                    <small class="text-muted">Jane Smith completed Module 5 - 1 day ago</small>
                                </div>
                                <div class="timeline-item mb-3">
                                    <h6 class="mb-1">Certification scheduled</h6>
                                    <small class="text-muted">Mike Johnson's PMP Prep - 3 days ago</small>
                                </div>
                                <div class="timeline-item">
                                    <h6 class="mb-1">Seminar feedback submitted</h6>
                                    <small class="text-muted">Sarah Wilson's Communication Skills - 1 week ago</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-chart-pie mr-2"></i>
                                Activity Distribution
                            </div>
                            <div class="card-body">
                                <canvas id="activitiesChart" width="100%" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add New Activity Modal -->
    <div class="modal fade" id="addActivityModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Schedule New Development Activity
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addActivityForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employeeSelect">Select Employee</label>
                                    <select class="form-control" id="employeeSelect" required>
                                        <option value="">Choose employee...</option>
                                        <option>John Doe</option>
                                        <option>Jane Smith</option>
                                        <option>Mike Johnson</option>
                                        <option>Sarah Wilson</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="activityType">Activity Type</label>
                                    <select class="form-control" id="activityType" required>
                                        <option value="">Select type...</option>
                                        <option>Workshop</option>
                                        <option>Online Course</option>
                                        <option>Certification</option>
                                        <option>Seminar</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="activityTitle">Activity Title</label>
                            <input type="text" class="form-control" id="activityTitle" placeholder="Enter activity title" required>
                        </div>
                        <div class="form-group">
                            <label for="activityDescription">Description</label>
                            <textarea class="form-control" id="activityDescription" rows="3" placeholder="Describe the development activity..." required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="startDate">Start Date</label>
                                    <input type="date" class="form-control" id="startDate" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="endDate">End Date</label>
                                    <input type="date" class="form-control" id="endDate" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="location">Location/Venue</label>
                            <input type="text" class="form-control" id="location" placeholder="Enter location or online platform" required>
                        </div>
                        <div class="form-group">
                            <label for="objectives">Learning Objectives</label>
                            <textarea class="form-control" id="objectives" rows="2" placeholder="List the main learning objectives..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="createActivity()">
                        <i class="fas fa-save mr-2"></i>
                        Schedule Activity
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Initialize Chart.js for activity distribution
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('activitiesChart').getContext('2d');
            const activitiesChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Workshop', 'Online Course', 'Certification', 'Seminar'],
                    datasets: [{
                        data: [6, 8, 5, 5],
                        backgroundColor: [
                            '#FF6B6B',
                            '#4ECDC4',
                            '#45B7D1',
                            '#F7DC6F'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'bottom'
                    }
                }
            });
        });

        // Form validation and submission
        function createActivity() {
            const form = document.getElementById('addActivityForm');
            if (form.checkValidity()) {
                // Here you would typically send the data to the server
                alert('Development activity scheduled successfully!');
                $('#addActivityModal').modal('hide');
                form.reset();
            } else {
                form.reportValidity();
            }
        }

        // Add some interactive features
        document.querySelectorAll('.btn-outline-primary').forEach(btn => {
            btn.addEventListener('click', function() {
                // View activity details
                alert('Viewing activity details...');
            });
        });

        document.querySelectorAll('.btn-outline-secondary').forEach(btn => {
            btn.addEventListener('click', function() {
                // Edit activity
                alert('Opening edit form...');
            });
        });

        document.querySelectorAll('.btn-outline-success').forEach(btn => {
            btn.addEventListener('click', function() {
                // Mark as complete
                if (confirm('Mark this activity as completed?')) {
                    alert('Activity marked as completed!');
                    location.reload();
                }
            });
        });
    </script>
</body>
</html>
