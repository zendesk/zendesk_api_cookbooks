require 'zendesk_api'
require 'json'
require_relative "../lib/client"

puts client.tickets.destroy_many(:ids => [198, 199, 200],
                                  :verb => :delete).fetch