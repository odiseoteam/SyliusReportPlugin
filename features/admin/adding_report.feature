@managing_reports
Feature: Adding a new report
    In order to show different data statistics
    As an Administrator
    I want to add a new report to the admin

    Background:
        Given I am logged in as an administrator

    @ui
    Scenario: Adding a new report
        Given I want to add a new report
        When I fill the code with "2018_sales"
        And I fill the name with "2018 Sales"
        And I fill the description with "Sales statistic for year 2018"
        And I add it
        Then I should be notified that it has been successfully created
        And the report "2018_sales" should appear in the admin

    @ui
    Scenario: Trying to add a new report with empty fields
        Given I want to add a new report
        When I fill the code with "2018_sales"
        And I add it
        Then I should be notified that the form contains invalid fields

    @ui
    Scenario: Trying to add a report with existing code
        Given there is an existing report with "2018_sales" code
        And I want to add a new report
        When I fill the code with "2018_sales"
        And I add it
        Then I should be notified that there is already an existing report with provided code
