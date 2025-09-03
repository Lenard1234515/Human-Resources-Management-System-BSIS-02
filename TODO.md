# TODO: Performance Review Cycles Implementation

## Overview
Implement a comprehensive performance review cycle management system in performance_review_cycles.php with full CRUD operations, employee participation management, and evaluation integration.

## Detailed Steps

### 1. UI Layout and Structure
- [x] Add main container with title "Performance Review Cycles"
- [x] Implement Bootstrap 5 responsive layout with container offset (margin-left: 265px)
- [x] Add "Add Cycle" button to trigger modal
- [x] Create table structure with columns: Cycle Name, Start Date, End Date, Status, Actions
- [x] Style table with proper borders and spacing

### 2. Review Cycle Management
- [x] Implement table to list all review cycles from database
- [x] Add status calculation logic (Upcoming/Ongoing/Closed based on dates)
- [x] Highlight ongoing cycles in green background
- [x] Add Edit and Delete buttons for each cycle
- [x] Add View Employees button for each cycle

### 3. Add Cycle Modal
- [x] Create Bootstrap modal for adding new review cycles
- [x] Add form fields: Cycle Name (text), Start Date (date), End Date (date)
- [x] Add form validation (end date after start date)
- [x] Implement submit functionality

### 4. Edit Cycle Modal
- [x] Create Bootstrap modal for editing existing cycles
- [x] Pre-populate form with current cycle data
- [x] Update form fields and validation
- [x] Implement update functionality

### 5. Delete Cycle Functionality
- [x] Add confirmation dialog for delete action
- [x] Implement delete functionality with proper error handling

### 6. View Employees Modal
- [x] Create modal to display employees assigned to a cycle
- [x] Show table with employee details and actions
- [x] Add "Assign Employee" dropdown/button
- [x] Add "Remove Employee" functionality

### 7. Employee Assignment
- [x] Create dropdown to select employees for assignment
- [x] Implement assignEmployeeToCycle function
- [x] Update employee list in modal after assignment
- [x] Handle duplicate assignments gracefully

### 8. Employee Removal
- [x] Add remove buttons for each assigned employee
- [x] Implement removeEmployeeFromCycle function
- [x] Update employee list in modal after removal

### 9. Evaluation Integration
- [ ] Link each employee to competency evaluation form
- [ ] Pull competencies from employee_competencies or role-based
- [ ] Create evaluation modal with rating inputs (1-5)
- [ ] Add comments field for each competency
- [ ] Calculate and display final score
- [ ] Save evaluation data to database

### 10. JavaScript Functions
- [x] Implement loadCycles() - fetch and display all cycles
- [x] Implement addCycle() - POST new cycle data
- [x] Implement editCycle(id) - fetch cycle details and update
- [x] Implement deleteCycle(id) - remove cycle
- [x] Implement viewEmployees(cycleId) - list employees in cycle
- [x] Implement assignEmployeeToCycle() - assign employee
- [x] Implement removeEmployeeFromCycle() - unassign employee
- [x] Add proper error handling and user feedback

### 11. PHP Backend Handlers
- [x] Create API endpoints for cycle CRUD operations
- [x] Create endpoints for employee assignment/removal
- [x] Create endpoints for evaluation data handling
- [x] Implement database queries for review_cycles table
- [x] Implement queries for employee_reviews and review_competencies tables
- [x] Add proper error handling and validation

### 12. Database Integration
- [x] Ensure review_cycles table exists with required columns
- [x] Ensure employee_reviews table exists
- [x] Ensure review_competencies table exists
- [x] Create tables if they don't exist
- [x] Add sample data for testing

### 13. Security and Session Management
- [x] Verify session check is working
- [x] Add CSRF protection for forms
- [x] Validate user permissions for operations
- [x] Sanitize all input data

### 14. Styling and UX
- [x] Ensure consistent Bootstrap 5 styling
- [x] Add FontAwesome icons throughout
- [x] Implement responsive design
- [x] Add loading indicators for AJAX calls
- [x] Add success/error message displays

### 15. Testing and Validation
- [ ] Test all CRUD operations for cycles
- [ ] Test employee assignment and removal
- [ ] Test evaluation form submission
- [ ] Test status calculation logic
- [ ] Test responsive layout on different screen sizes
- [ ] Validate all form inputs
- [ ] Test error handling scenarios

### 16. Optional Features
- [ ] Implement PDF export functionality
- [ ] Add search/filter functionality for cycles
- [ ] Add pagination for large datasets
- [ ] Add bulk operations for employee assignment

## Dependencies
- performance_review_cycles.php (main file)
- dp.php (database connection)
- navigation.php (top navigation)
- sidebar.php (side menu)
- styles.css (custom styles)
- employee_competencies.php (for evaluation integration)
- get_employees.php (for employee dropdown)
- get_competencies.php (for competency data)

## Notes
- Use fetch API for all AJAX calls
- Follow existing code patterns from other HR pages
- Ensure compatibility with existing database schema
- Test thoroughly before deployment
