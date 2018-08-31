@managing_reports
Feature: Seeing reports details
    In order to see reports details in the store
    As an Administrator
    I want to be able to show specific reports page

    Background:
        Given there is an existing report with "2018_sales" code
        And I am logged in as an administrator
        And the store operates on a single channel in "United States"

    @ui
    Scenario: Seeing reports basic information
        When I view details of the report "2018_sales"
        Then his code should be "2018_sales"
        And his name should be "Sales 2018"
        And his description should be "Sales statistics for year 2018"
