require 'zendesk_api'
require 'json'
require_relative "../lib/client"

#Create new ticket with custom field and tags
ticket = client.ticket.create(:subject => "Test Ticket", 
                              :comment => { :value => "This is a test"}, 
                              :submitter_id => client.current_user.id, 
                              :priority => "normal", 
                              :custom_fields => {:id => "21833683", :value => "Halo 4"},
                              :metadata => { :time_spent => "4m12s", :account => "integrations"},
                              :tags => ["test1", "test2", "test3"])

puts ticket.inspect

#View audit information to see metadata
audit = ticket.audits
puts audit.first.metadata
