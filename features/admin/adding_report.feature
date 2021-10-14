@managing_reports
Feature: Adding a new report
    In order to show different data statistics
    As an Administrator
    I want to add a new report to the admin

    Background:
        Given I am logged in as an administrator
        And the store operates on a single channel in "United States"

    @ui
    Scenario: Adding a new report
        Given I want to add a new report
        When I fill the code with "2018_user_registration"
        And I fill the name with "2018 User Registration"
        And I fill the description with "User registrations for year 2018"
        And I add it
        Then I should be notified that it has been successfully created
        And the report "2018_user_registration" should appear in the admin

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

    @ui
    Scenario: Adding a new report with data fetcher
        Given I want to add a new report
        When I fill the code with "2018_sales"
        And I fill the name with "2018 Sales"
        And I fill the description with "Sales statistic for year 2018"
        And I select "odiseo_sylius_report_plugin_data_fetcher_sales_total" as data fetcher
        And I select "2018-01-01" as start date
        And I select today as end date
        And I select "month" as time period
        And I add it
        Then I should be notified that it has been successfully created
        And the report "2018_sales" should appear in the admin
