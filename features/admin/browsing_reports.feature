@managing_reports
Feature: Browsing reports
    In order to show different data statistics
    As an Administrator
    I want to be able to browse reports

    Background:
        Given the store has "2000_sales" and "2001_sales" reports
        And I am logged in as an administrator
        And the store operates on a single channel in "United States"

    @ui
    Scenario: Browsing defined reports
        When I want to browse reports
        Then I should see 2 reports in the list
