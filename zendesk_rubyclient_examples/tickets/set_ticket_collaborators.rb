# Collaborators can be set by ID, email, or hash containing their full name and email
# Note that setting collaborators this way will completely ignore what's already set.
# Make sure to include existing collaborators in the array if you wish to retain these on the ticket.

require 'zendesk_api'
require 'json'
require_relative "../lib/client"

#Create new ticket with custom field and tags
ticket = client.ticket.create(:subject => "Test Ticket", 
                              :comment => { :value => "This is a test"},
                              :collaborators => [ 246285576, "devaris@devaris.com"],
                              :submitter_id => client.current_user.id, 
                              :priority => "normal", 
                              :custom_fields => {:id => "21833683", :value => "Halo 4"},
                              :tags => ["test1", "test2", "test3"])
puts ticket.inspect
