require 'zendesk_api'
require 'json'
require_relative "../lib/client"

tickets = client.tickets.update_many(:ids => [151, 156, 161], 
                                        :ticket => { :tags => ["update_many_1", "update_many_2", "update_many_3"] }, 
                                        :verb => :put)

tickets.fetch!

puts tickets.inspect