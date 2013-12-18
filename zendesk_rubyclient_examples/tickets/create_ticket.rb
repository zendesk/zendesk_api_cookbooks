require 'zendesk_api'
require 'json'
require_relative "../lib/client"

#Create new ticket with custom field and tags
puts client.tickets.create(:subject => "Test Ticket", 
                              :comment => { :value => "This is a test"}, 
                              :submitter_id => client.current_user.id, 
                              :priority => "normal",
                              :type => "incident",
                              :custom_fields => {:id => "21833683", :value => "Halo 4"},
                              :tags => ["test1", "test2", "test3"])