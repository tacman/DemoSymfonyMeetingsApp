Feature: Rate meeting
  In order to rate the meeting
  The meeting has to be finished and user have to be a participant of this meeting

  Background:
    Given the app has a finished meeting
    And the app has a user which is a participant of the meeting

  Scenario:
    When the app sends a request to rate the meeting by the user with rate "3"
    Then the meeting should be rated with rate "3"
