@managing_reports
Feature: Editing a report
    In order to change data statistics
    As an Administrator
    I want to be able to edit a report

    Background:
        Given I am logged in as an administrator
        And the store operates on a single channel in "United States"
        And there is an existing report with "2018_sales" code

    @ui
    Scenario: Renaming a report
        Given I want to modify the report "2018_sales"
        When I rename it to "Sales 2018"
        And I save my changes
        Then I should be notified that it has been successfully edited
