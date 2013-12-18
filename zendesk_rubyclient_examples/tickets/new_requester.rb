require 'zendesk_api'
require 'json'
require_relative "../lib/client"

ticket = client.tickets.create(:subject => "Test Ticket", 
                              :comment => { :value => "This is a test"}, 
                              :submitter_id => client.current_user.id,
                              :requester => { :name => "Tito Jackson", :email => "titoj@example.com"},
                              :priority => "normal", 
                              :custom_fields => {:id => "21833683", :value => "Halo 4"},
                              :tags => ["test1", "test2", "test3"])

puts ticket.inspect